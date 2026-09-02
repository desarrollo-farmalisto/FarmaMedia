<?php

declare(strict_types=1);

final class AuthController
{
    public function showLogin(): void
    {
        if (!empty($_SESSION['user_email'])) {
            header('Location: ' . APP_URL . '/');
            exit;
        }
        view('auth/login', ['pageTitle' => 'Iniciar sesión', 'error' => '']);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $error = '';

        if ($email === '') {
            $error = 'Ingresa tu correo electrónico.';
        } else {
            $db   = Database::connect();
            $stmt = $db->prepare('SELECT email, rol FROM users WHERE email = :email AND status = 1 LIMIT 1');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_rol']   = $user['rol'];
                header('Location: ' . APP_URL . '/');
                exit;
            }

            $error = 'Este usuario no existe o no tiene acceso.';
        }

        view('auth/login', ['pageTitle' => 'Iniciar sesión', 'error' => $error]);
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ' . APP_URL . '/login');
        exit;
    }
}
