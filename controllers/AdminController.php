<?php

declare(strict_types=1);

final class AdminController //
{
    public function index(): void
    {
        $db = Database::connect();

        $total     = $db->query('SELECT COUNT(*) FROM fm_recursos')->fetchColumn();
        $recientes = $db->query('SELECT COUNT(*) FROM fm_recursos WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')->fetchColumn();

        $porPagina = 10;
        $pagina    = max(1, (int)($_GET['pagina'] ?? 1));
        $offset    = ($pagina - 1) * $porPagina;
        $totalPags = (int)ceil((int)$total / $porPagina);

        $ultimos = $db->query(
            'SELECT r.*, a.nombre, a.descripcion, a.archivo, a.link, COUNT(c.id) AS total_comentarios
             FROM fm_recursos r
             LEFT JOIN fm_archivos a ON a.id = (SELECT id FROM fm_archivos WHERE recurso_id = r.id ORDER BY id ASC LIMIT 1)
             LEFT JOIN fm_comments c ON c.fm_recurso_id = r.id AND c.status = 1
             GROUP BY r.id, a.nombre, a.descripcion, a.archivo, a.link
             ORDER BY r.created_at DESC
             LIMIT ' . $porPagina . ' OFFSET ' . $offset
        )->fetchAll();

        view('admin/index', [
            'pageTitle'  => 'Panel de control',
            'stats' => [
                ['label' => 'Recursos publicados', 'value' => str_pad((string) $total,     2, '0', STR_PAD_LEFT), 'change' => 'Total'],
                ['label' => 'Nuevos esta semana',  'value' => str_pad((string) $recientes, 2, '0', STR_PAD_LEFT), 'change' => 'Últimos 7 días'],
            ],
            'ultimos'    => $ultimos,
            'pagina'     => $pagina,
            'totalPags'  => $totalPags,
        ]);
    }

    public function comments(string $id): void
    {
        $db   = Database::connect();
        $stmt = $db->prepare(
            'SELECT description, created_at FROM fm_comments WHERE fm_recurso_id = :id AND status = 1 ORDER BY created_at DESC'
        );
        $stmt->execute([':id' => $id]);

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }
}
