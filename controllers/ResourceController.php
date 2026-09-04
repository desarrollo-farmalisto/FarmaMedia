<?php

declare(strict_types=1);

final class ResourceController //
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index(): void
    {
        $cuadernos = $this->db->query(
            'SELECT r.*, a.nombre, a.descripcion, a.archivo, a.link
             FROM fm_recursos r
             LEFT JOIN fm_archivos a ON a.id = (SELECT id FROM fm_archivos WHERE recurso_id = r.id ORDER BY id ASC LIMIT 1)
             WHERE r.modo_cuaderno = 1
             ORDER BY r.created_at DESC'
        )->fetchAll();

        $normales = $this->db->query(
            'SELECT r.id, r.tipo, r.modo_cuaderno, r.created_at, a.id AS archivo_id, a.nombre, a.descripcion, a.archivo, a.link
             FROM fm_recursos r
             LEFT JOIN fm_archivos a ON a.recurso_id = r.id
             WHERE r.modo_cuaderno = 0
             ORDER BY r.created_at DESC, a.id ASC'
        )->fetchAll();

        $recursos = array_merge($cuadernos, $normales);
        usort($recursos, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        view('admin/recursos/index', ['pageTitle' => 'Recursos', 'recursos' => $recursos]);
    }

    public function create(): void
    {
        view('admin/recursos/create', ['pageTitle' => 'Nuevo recurso', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        file_put_contents(APP_ROOT . '/public/uploads/store_debug.log', date('Y-m-d H:i:s') . PHP_EOL . 'POST: ' . print_r($_POST, true) . PHP_EOL . 'FILES: ' . print_r($_FILES, true) . PHP_EOL);
        $data           = $this->sanitize($_POST);
        $files          = $this->normalizeFiles($_FILES['archivo'] ?? []);
        $errors         = $this->validate($data, $files);
        $modoCuaderno   = isset($_POST['modo_cuaderno']) ? 1 : 0;
        $ordenes        = $_POST['orden'] ?? [];
        $infoIndividual = (int)($_POST['info_individual'] ?? 0);
        $titulos        = $_POST['titulo_individual'] ?? [];
        $descripciones  = $_POST['descripcion_individual'] ?? [];
        $links          = $_POST['link_individual'] ?? [];

        if ($errors) {
            view('admin/recursos/create', ['pageTitle' => 'Nuevo recurso', 'errors' => $errors, 'old' => $data]);
            return;
        }

        // Crear el recurso contenedor
        $stmt = $this->db->prepare(
            'INSERT INTO fm_recursos (tipo, modo_cuaderno, status, created_at) VALUES (:tipo, :modo_cuaderno, 1, NOW())'
        );
        $stmt->execute([':tipo' => $data['tipo'], ':modo_cuaderno' => $modoCuaderno]);
        $recursoId = (int)$this->db->lastInsertId();

        // Insertar cada archivo
        $stmtA = $this->db->prepare(
            'INSERT INTO fm_archivos (recurso_id, nombre, descripcion, archivo, link, orden)
             VALUES (:recurso_id, :nombre, :descripcion, :archivo, :link, :orden)'
        );

        foreach ($files as $i => $file) {
            $filePath = $this->handleUpload($file, $data['tipo']);
            $orden    = $modoCuaderno ? (int)($ordenes[$i] ?? $i + 1) : null;

            if ($infoIndividual) {
                $nombre      = trim($titulos[$i] ?? '') ?: $data['nombre'];
                $descripcion = trim($descripciones[$i] ?? '') ?: $data['descripcion'];
                $link        = trim($links[$i] ?? '') ?: $data['link'];
            } else {
                $nombre      = $data['nombre'];
                $descripcion = $data['descripcion'];
                $link        = $data['link'];
            }

            $stmtA->execute([
                ':recurso_id'  => $recursoId,
                ':nombre'      => $nombre,
                ':descripcion' => $descripcion,
                ':archivo'     => $filePath,
                ':link'        => $link,
                ':orden'       => $orden,
            ]);
        }

        header('Location: ' . APP_URL . '/admin/recursos');
        exit;
    }

    public function edit(string $id): void
    {
        $recurso  = $this->findOrFail($id);
        $archivos = $this->db->prepare('SELECT * FROM fm_archivos WHERE recurso_id = :id ORDER BY orden ASC, id ASC');
        $archivos->execute([':id' => $id]);
        $archivos = $archivos->fetchAll();

        view('admin/recursos/edit', [
            'pageTitle' => 'Editar recurso',
            'recurso'   => $recurso,
            'archivos'  => $archivos,
            'errors'    => [],
            'old'       => array_merge($recurso, $archivos[0] ?? []),
        ]);
    }

    public function update(string $id): void
    {
        $recurso  = $this->findOrFail($id);
        $data     = $this->sanitize($_POST);
        $files    = $this->normalizeFiles($_FILES['archivo'] ?? []);
        $errors   = $this->validate($data, $files, true);

        if ($errors) {
            $archivos = $this->db->prepare('SELECT * FROM fm_archivos WHERE recurso_id = :id ORDER BY orden ASC, id ASC');
            $archivos->execute([':id' => $id]);
            view('admin/recursos/edit', [
                'pageTitle' => 'Editar recurso',
                'recurso'   => $recurso,
                'archivos'  => $archivos->fetchAll(),
                'errors'    => $errors,
                'old'       => $data,
            ]);
            return;
        }

        $this->db->prepare('UPDATE fm_recursos SET tipo=:tipo WHERE id=:id')
            ->execute([':tipo' => $data['tipo'], ':id' => $id]);

        // Si se suben nuevos archivos, reemplazar todos
        if (!empty($files)) {
            $existing = $this->db->prepare('SELECT archivo FROM fm_archivos WHERE recurso_id = :id');
            $existing->execute([':id' => $id]);
            foreach ($existing->fetchAll() as $e) {
                $full = APP_ROOT . '/public/uploads/' . $e['archivo'];
                if ($e['archivo'] && file_exists($full)) unlink($full);
            }
            $this->db->prepare('DELETE FROM fm_archivos WHERE recurso_id = :id')->execute([':id' => $id]);

            $stmtA = $this->db->prepare(
                'INSERT INTO fm_archivos (recurso_id, nombre, descripcion, archivo, link, orden)
                 VALUES (:recurso_id, :nombre, :descripcion, :archivo, :link, :orden)'
            );
            foreach ($files as $i => $file) {
                $stmtA->execute([
                    ':recurso_id'  => $id,
                    ':nombre'      => $data['nombre'],
                    ':descripcion' => $data['descripcion'],
                    ':archivo'     => $this->handleUpload($file, $data['tipo']),
                    ':link'        => $data['link'],
                    ':orden'       => $i + 1,
                ]);
            }
        } else {
            // Solo actualizar nombre/descripcion/link del primer archivo
            $this->db->prepare(
                'UPDATE fm_archivos SET nombre=:nombre, descripcion=:descripcion, link=:link WHERE recurso_id=:id'
            )->execute([
                ':nombre'      => $data['nombre'],
                ':descripcion' => $data['descripcion'],
                ':link'        => $data['link'],
                ':id'          => $id,
            ]);
        }

        header('Location: ' . APP_URL . '/admin/recursos');
        exit;
    }

    public function destroy(string $id): void
    {
        $this->findOrFail($id);

        $archivos = $this->db->prepare('SELECT archivo FROM fm_archivos WHERE recurso_id = :id');
        $archivos->execute([':id' => $id]);
        foreach ($archivos->fetchAll() as $a) {
            $full = APP_ROOT . '/public/uploads/' . $a['archivo'];
            if ($a['archivo'] && file_exists($full)) unlink($full);
        }

        $this->db->prepare('DELETE FROM fm_recursos WHERE id = :id')->execute([':id' => $id]);

        header('Location: ' . APP_URL . '/admin/recursos');
        exit;
    }

    public function comment(string $id): void
    {
        $this->findOrFail($id);
        $description = trim($_POST['description'] ?? '');

        if ($description !== '') {
            $stmt = $this->db->prepare(
                'INSERT INTO fm_comments (description, fm_recurso_id, status, created_at)
                 VALUES (:description, :recurso_id, 1, NOW())'
            );
            $stmt->execute([':description' => $description, ':recurso_id' => $id]);
        }

        header('Location: ' . APP_URL . '/recursos#recurso-' . $id);
        exit;
    }

    // ── Helpers ──────────────────────────────────────────

    private function normalizeFiles(array $files): array
    {
        if (empty($files['name'])) return [];
        $result = [];
        $names = is_array($files['name']) ? $files['name'] : [$files['name']];
        $tmps  = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
        $errs  = is_array($files['error']) ? $files['error'] : [$files['error']];
        foreach ($names as $i => $name) {
            if ($errs[$i] !== UPLOAD_ERR_NO_FILE && $name !== '') {
                $result[] = ['name' => $name, 'tmp_name' => $tmps[$i], 'error' => $errs[$i]];
            }
        }
        return $result;
    }

    private function findOrFail(string $id): array
    {
        $stmt = $this->db->prepare('SELECT * FROM fm_recursos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            view('errors/404', ['pageTitle' => 'No encontrado']);
            exit;
        }
        return $row;
    }

    private function sanitize(array $post): array
    {
        return [
            'nombre'      => trim($post['nombre'] ?? ''),
            'descripcion' => trim($post['descripcion'] ?? ''),
            'tipo'        => trim($post['tipo'] ?? ''),
            'link'        => trim($post['link'] ?? ''),
        ];
    }

    private function validate(array $data, array $files, bool $editing = false): array
    {
        $errors = [];

        if ($data['nombre'] === '') $errors['nombre'] = 'El nombre es obligatorio.';
        if ($data['descripcion'] === '') $errors['descripcion'] = 'La descripción es obligatoria.';
        if (!array_key_exists($data['tipo'], ALLOWED_EXTENSIONS)) $errors['tipo'] = 'Selecciona un tipo válido.';

        if (!$editing && empty($files)) {
            $errors['archivo'] = 'Debes subir al menos un archivo.';
        } else {
            $allowed = ALLOWED_EXTENSIONS[$data['tipo']] ?? [];
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed, true)) {
                    $errors['archivo'] = 'Extensión no permitida. Permitidas: ' . implode(', ', $allowed);
                    break;
                }
            }
        }

        return $errors;
    }

    private function handleUpload(array $file, string $tipo): string
    {
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $tipo . '_' . uniqid() . '.' . $ext;
        $dest     = APP_ROOT . '/public/uploads/' . $filename;

        if (!is_dir(APP_ROOT . '/public/uploads')) {
            mkdir(APP_ROOT . '/public/uploads', 0755, true);
        }

        move_uploaded_file($file['tmp_name'], $dest);
        return $filename;
    }
}
