<?php

declare(strict_types=1);

final class AdminController
{
    public function index(): void
    {
        auth();
        if (!isAdmin()) {
            header('Location: ' . APP_URL . '/');
            exit;
        }
        $db = Database::connect();

        $total     = $db->query('SELECT COUNT(*) FROM fm_recursos')->fetchColumn();
        $recientes = $db->query('SELECT COUNT(*) FROM fm_recursos WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')->fetchColumn();
        $ultimos   = $db->query('SELECT * FROM fm_recursos ORDER BY created_at DESC LIMIT 5')->fetchAll();

        view('admin/index', [
            'pageTitle' => 'Panel de control',
            'stats' => [
                ['label' => 'Recursos publicados', 'value' => str_pad((string) $total,     2, '0', STR_PAD_LEFT), 'change' => 'Total'],
                ['label' => 'Nuevos esta semana',  'value' => str_pad((string) $recientes, 2, '0', STR_PAD_LEFT), 'change' => 'Últimos 7 días'],
            ],
            'ultimos' => $ultimos,
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
