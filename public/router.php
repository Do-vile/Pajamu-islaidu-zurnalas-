<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // leisti PHP built-in serveriui atiduoti failą (CSS, JS, img)
}

require __DIR__ . '/index.php';

