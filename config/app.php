<?php

declare(strict_types=1);

const APP_NAME = 'Farmalisto';//

// Local URL
// const APP_URL  = 'http://farmamedia.test';
// Production URL
const APP_URL  = 'https://farmalisto.farmamedia.com.co';
const APP_ROOT = __DIR__ . '/..';

// Local Database Configuration
const DB_HOST = 'localhost';
const DB_NAME = 'farmamedia';
const DB_USER = 'root';
const DB_PASS = '';

// Production Database Configuration
// const DB_HOST = 'localhost';
// const DB_NAME = 'farmamedia';
// const DB_USER = 'root';
// const DB_PASS = '';



const UPLOAD_PATH = APP_ROOT . '/public/uploads';
const UPLOAD_URL  = APP_URL  . '/public/uploads';

const ALLOWED_EXTENSIONS = [
    'imagen' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
    'video'  => ['mp4', 'webm', 'mov', 'avi'],
    'gif'    => ['gif'],
    'audio'  => ['mp3', 'wav', 'ogg'],
    'documento' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'],
    'otro'   => ['zip', 'rar'],
];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require APP_ROOT . '/views/' . $template . '.php';
}

function auth(): void
{
    if (empty($_SESSION['user_email'])) {
        header('Location: ' . APP_URL . '/login');
        exit;
    }
}

function isAdmin(): bool
{
    return ($_SESSION['user_rol'] ?? '') === 'admin';
}
