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
        $recursos = $this->db->query('SELECT * FROM fm_recursos ORDER BY created_at DESC')->fetchAll();
        view('admin/recursos/index', ['pageTitle' => 'Recursos', 'recursos' => $recursos]);
    }

    public function create(): void
    {
        view('admin/recursos/create', ['pageTitle' => 'Nuevo recurso', 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        $data   = $this->sanitize($_POST);
        $errors = $this->validate($data, $_FILES['archivo'] ?? null);

        if ($errors) {
            view('admin/recursos/create', ['pageTitle' => 'Nuevo recurso', 'errors' => $errors, 'old' => $data]);
            return;
        }

        $filePath = $this->handleUpload($_FILES['archivo'], $data['tipo']);

        $stmt = $this->db->prepare(
            'INSERT INTO fm_recursos (nombre, descripcion, tipo, archivo, link, created_at)
             VALUES (:nombre, :descripcion, :tipo, :archivo, :link, NOW())'
        );
        $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':tipo'        => $data['tipo'],
            ':archivo'     => $filePath,
            ':link'        => $data['link'],
        ]);

        header('Location: ' . APP_URL . '/admin/recursos');
        exit;
    }

    public function edit(string $id): void
    {
        $recurso = $this->findOrFail($id);
        view('admin/recursos/edit', ['pageTitle' => 'Editar recurso', 'recurso' => $recurso, 'errors' => [], 'old' => $recurso]);
    }

    public function update(string $id): void
    {
        $recurso = $this->findOrFail($id);
        $data    = $this->sanitize($_POST);
        $errors  = $this->validate($data, $_FILES['archivo'] ?? null, true);

        if ($errors) {
            view('admin/recursos/edit', ['pageTitle' => 'Editar recurso', 'recurso' => $recurso, 'errors' => $errors, 'old' => $data]);
            return;
        }

        $filePath = $recurso['archivo'];
        if (!empty($_FILES['archivo']['name'])) {
            $filePath = $this->handleUpload($_FILES['archivo'], $data['tipo']);
        }

        $stmt = $this->db->prepare(
            'UPDATE fm_recursos SET nombre=:nombre, descripcion=:descripcion, tipo=:tipo, archivo=:archivo, link=:link
             WHERE id=:id'
        );
        $stmt->execute([
            ':nombre'      => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':tipo'        => $data['tipo'],
            ':archivo'     => $filePath,
            ':link'        => $data['link'],
            ':id'          => $id,
        ]);

        header('Location: ' . APP_URL . '/admin/recursos');
        exit;
    }

    public function destroy(string $id): void
    {
        $recurso = $this->findOrFail($id);

        if ($recurso['archivo']) {
            $full = APP_ROOT . '/public/uploads/' . $recurso['archivo'];
            if (file_exists($full)) unlink($full);
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

    private function validate(array $data, ?array $file, bool $editing = false): array
    {
        $errors = [];

        if ($data['nombre'] === '') $errors['nombre'] = 'El nombre es obligatorio.';
        if ($data['descripcion'] === '') $errors['descripcion'] = 'La descripción es obligatoria.';
        if (!array_key_exists($data['tipo'], ALLOWED_EXTENSIONS)) $errors['tipo'] = 'Selecciona un tipo válido.';

        if (!$editing && (empty($file['name']) || $file['error'] === UPLOAD_ERR_NO_FILE)) {
            $errors['archivo'] = 'Debes subir un archivo.';
        } elseif (!empty($file['name']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ALLOWED_EXTENSIONS[$data['tipo']] ?? [];
            if (!in_array($ext, $allowed, true)) {
                $errors['archivo'] = 'Extensión no permitida para este tipo. Permitidas: ' . implode(', ', $allowed);
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
