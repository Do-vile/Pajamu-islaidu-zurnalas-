<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$file = __DIR__ . $path;

// Jei prašomas realus failas public/ kataloge (css, js, img) – atiduodam jį tiesiai
if ($path !== '/' && is_file($file)) {
    return false;
}

// Visa kita – per pagrindinį app entrypoint
require __DIR__ . '/index.php';
