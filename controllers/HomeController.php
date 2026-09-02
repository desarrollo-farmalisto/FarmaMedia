<?php

declare(strict_types=1);

final class HomeController //
{
    public function index(): void
    {
        auth();
        $db       = Database::connect();
        $recursos = $db->query('SELECT * FROM fm_recursos WHERE status = 1 ORDER BY created_at DESC')->fetchAll();

        view('home/index', [
            'pageTitle' => 'Recursos que se hacen notar',
            'recursos'  => $recursos,
        ]);
    }
}
