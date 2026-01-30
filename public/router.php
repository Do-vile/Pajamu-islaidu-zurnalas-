<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// 🔴 LEIDŽIAM STATINIAMS FAILAMS EITI TIESIAI
if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

require __DIR__ . '/index.php';
<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // leisti PHP built-in serveriui atiduoti failą (CSS, JS, img)
}

require __DIR__ . '/index.php';

