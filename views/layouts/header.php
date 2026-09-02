<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME) ?> · <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/app.css" rel="stylesheet">
</head>
<body>
<header class="site-header"><div class="container d-flex align-items-center justify-content-between"><a class="brand" href="<?= APP_URL ?>/"><img src="<?= APP_URL ?>/public/images/logo--horizontal.png" alt="FarmaMedia" class="brand-mark" style="width:10rem;height:auto;min-width:10rem;display:block;object-fit:contain;border-radius:.5rem;"></a><nav class="d-none d-md-flex align-items-center gap-4"><a href="<?= APP_URL ?>/">Recursos</a><?php if (isAdmin()): ?><a class="nav-admin" href="<?= APP_URL ?>/admin">Admin <i class="bi bi-arrow-up-right"></i></a><?php endif; ?><a href="<?= APP_URL ?>/logout" class="nav-logout" title="Cerrar sesión"><i class="bi bi-box-arrow-right"></i></a></nav><button class="mobile-menu d-md-none" type="button" aria-label="Abrir menú"><i class="bi bi-list"></i></button></div></header>

    