<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Pajamų ir išlaidų žurnalas', ENT_QUOTES) ?></title>
  <link rel="stylesheet" href="/styles.css?v=2">
    
</head>
<body>
<div class="app-shell">
    <?php if ($user): ?>
        <header class="topbar">
            <div class="brand">Pajamų ir išlaidų žurnalas</div>
            <nav class="nav nav--pills">
                <a class="nav-link nav-link--pill <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>" href="/?page=dashboard">Bendra</a>
                <a class="nav-link nav-link--pill <?= ($activePage ?? '') === 'documents' ? 'active' : '' ?>" href="/?page=documents">Dokumentai</a>
                <a class="nav-link nav-link--pill <?= ($activePage ?? '') === 'journal' ? 'active' : '' ?>" href="/?page=journal">Žurnalas</a>
                <a class="nav-link nav-link--pill <?= ($activePage ?? '') === 'occupancy' ? 'active' : '' ?>" href="/?page=occupancy">Užimtumas</a>
            </nav>
            <div class="topbar-meta">
                <div class="user-email"><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></div>
                <a class="logout" href="/?action=logout">Atsijungti</a>
            </div>
        </header>
    <?php endif; ?>

    <main class="content <?= $user ? '' : 'content--centered' ?>">
        <?php if ($flash): ?>
            <div class="flash flash--<?= htmlspecialchars($flash['type'], ENT_QUOTES) ?>">
                <?= htmlspecialchars($flash['message'], ENT_QUOTES) ?>
            </div>
        <?php endif; ?>
