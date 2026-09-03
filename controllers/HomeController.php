<?php

declare(strict_types=1);

final class HomeController //
{
    public function index(): void
    {
        $db = Database::connect();

        $stmt = $db->query(
            'SELECT r.id, r.tipo, r.modo_cuaderno, r.status, r.created_at
             FROM fm_recursos r
             WHERE r.status = 1
             ORDER BY r.created_at DESC'
        );
        $recursos = $stmt->fetchAll();

        // Cargar archivos de cada recurso
        $stmtA = $db->prepare(
            'SELECT * FROM fm_archivos WHERE recurso_id = :id ORDER BY orden ASC, id ASC'
        );
        foreach ($recursos as &$r) {
            $stmtA->execute([':id' => $r['id']]);
            $r['archivos'] = $stmtA->fetchAll();
        }
        unset($r);

        view('home/index', [
            'pageTitle' => 'Recursos que se hacen notar',
            'recursos'  => $recursos,
        ]);
    }
}
