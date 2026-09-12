<?php

define('APP_NAME', 'Village Meeting System');
define('APP_BASE_PATH', __DIR__ . '/..');

define('APP_URL', getenv('APP_URL') ?: '/village meeting projects');

function app_url(string $path = ''): string
{
    $base = rtrim(APP_URL, '/');
    $rel = ltrim($path, '/');

    return $base . ($rel !== '' ? '/' . $rel : '');
}

function redirect(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}
