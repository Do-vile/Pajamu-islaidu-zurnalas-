<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Jei prašomas realus failas public kataloge (CSS/JS/img) – atiduodam jį tiesiai
$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}

// Visa kita – į index.php (tavo router logika)
require __DIR__ . '/index.php';


