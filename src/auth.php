<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
            'use_strict_mode' => true,
        ]);
    }
}

function current_user(): ?array
{
    start_session();

    $userId = $_SESSION['user_id'] ?? null;
    if (!is_int($userId) && !ctype_digit((string)$userId)) {
        return null;
    }

    $stmt = db()->prepare('SELECT id, email, created_at FROM users WHERE id = :id');
    $stmt->execute([':id' => (int)$userId]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function require_auth(): array
{
    $user = current_user();
    if ($user === null) {
        header('Location: /?page=login');
        exit;
    }
    return $user;
}

function login_user(string $email, string $password): ?string
{
    $email = mb_strtolower(trim($email));

    if ($email === '' || $password === '') {
        return 'Įveskite el. paštą ir slaptažodį.';
    }

    $stmt = db()->prepare('SELECT id, email, password_hash FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return 'Neteisingi prisijungimo duomenys.';
    }

    start_session();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];

    return null;
}

function register_user(string $email, string $password, string $passwordConfirm): ?string
{
    $email = mb_strtolower(trim($email));

    if ($email === '' || $password === '' || $passwordConfirm === '') {
        return 'Užpildykite visus laukus.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Neteisingas el. pašto formatas.';
    }

    if (mb_strlen($password) < 6) {
        return 'Slaptažodis turi būti bent 6 simbolių.';
    }

    if ($password !== $passwordConfirm) {
        return 'Slaptažodžiai nesutampa.';
    }

    $pdo = db();

    // ar jau egzistuoja
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        return 'Toks vartotojas jau egzistuoja.';
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $insert = $pdo->prepare('INSERT INTO users (email, password_hash) VALUES (:email, :hash)');
    $insert->execute([
        ':email' => $email,
        ':hash' => $hash,
    ]);

    start_session();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$pdo->lastInsertId();

    return null;
}

function logout_user(): void
{
    start_session();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

