<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function flash_set(string $type, string $message): void
{
    start_session();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function flash_get(): ?array
{
    start_session();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_array($flash) ? $flash : null;
}

function render(string $view, array $params = []): void
{
    $flash = flash_get();
    $user = current_user();

    extract($params, EXTR_SKIP);
    require __DIR__ . '/../views/' . $view . '.php';
}

