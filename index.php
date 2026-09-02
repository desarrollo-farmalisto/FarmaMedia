<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/ResourceController.php';

$router = new Router();

$router->get('/recursos',  [HomeController::class, 'index']);
$router->post('/recursos/{id}/comentar', [ResourceController::class, 'comment']);
$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/recursos/{id}/comentarios', [AdminController::class, 'comments']);

$router->get('/admin/recursos',              [ResourceController::class, 'index']);
$router->get('/admin/recursos/crear',        [ResourceController::class, 'create']);
$router->post('/admin/recursos/crear',       [ResourceController::class, 'store']);
$router->get('/admin/recursos/{id}/editar',  [ResourceController::class, 'edit']);
$router->post('/admin/recursos/{id}/editar', [ResourceController::class, 'update']);
$router->post('/admin/recursos/{id}/borrar', [ResourceController::class, 'destroy']);

$router->dispatch(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

