<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> · <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/public/assets/css/app.css" rel="stylesheet">
    <style>
        body { background: var(--bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }

        .login-wrap {
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }

        .login-card {
            background: var(--surface);
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 8px 40px rgba(0,0,0,.08);
        }

        .login-logo {
            display: block;
            margin: 0 auto 2rem;
            max-width: 180px;
            height: auto;
        }

        .login-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: .4rem;
            color: var(--text);
        }

        .login-sub {
            text-align: center;
            color: var(--muted);
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        .login-input {
            width: 100%;
            padding: .85rem 1.1rem;
            border: 1.5px solid rgba(0,0,0,.1);
            border-radius: .75rem;
            font-size: .95rem;
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: border-color .2s;
            outline: none;
        }

        .login-input:focus {
            border-color: var(--accent);
        }

        .login-input.is-error {
            border-color: var(--highlight);
        }

        .login-error {
            background: rgba(255,90,107,.1);
            color: var(--highlight);
            border-radius: .6rem;
            padding: .65rem 1rem;
            font-size: .88rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .btn-login {
            width: 100%;
            padding: .85rem;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: .75rem;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .1s;
            margin-top: 1rem;
        }

        .btn-login:hover  { background: #017a85; }
        .btn-login:active { transform: scale(.98); }

        .login-footer {
            text-align: center;
            color: var(--muted);
            font-size: .8rem;
            margin-top: 2rem;
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.25rem; border-radius: 1.25rem; }
            .login-logo { max-width: 150px; }
        }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-card">

        <img src="<?= APP_URL ?>/public/images/logo--horizontal.png" alt="<?= APP_NAME ?>" class="login-logo">

        <h1 class="login-title">Bienvenido</h1>
        <p class="login-sub">Ingresa tu correo para acceder.</p>

        <?php if ($error): ?>
            <div class="login-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/login">
            <input
                type="email"
                name="email"
                class="login-input<?= $error ? ' is-error' : '' ?>"
                placeholder="tucorreo@ejemplo.com"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                autofocus
                required
            >
            <button type="submit" class="btn-login">
                Validar <i class="bi bi-arrow-right"></i>
            </button>
        </form>

    </div>
    <p class="login-footer">&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
</div>
</body>
</html>
