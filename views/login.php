<?php declare(strict_types=1); ?>
<?php require __DIR__ . '/partials/header.php'; ?>

<section class="auth-card">
    <div class="auth-header">
        <h1>Pajamų ir išlaidų žurnalas</h1>
        <p>Prisijunkite arba susikurkite paskyrą individualiai veiklai.</p>
    </div>

    <div class="auth-grid">
        <form class="card" method="post" action="/?action=login">
            <h2>Prisijungimas</h2>
            <label class="field">
                <span>El. paštas</span>
                <input type="email" name="email" autocomplete="email" required>
            </label>
            <label class="field">
                <span>Slaptažodis</span>
                <input type="password" name="password" autocomplete="current-password" required>
            </label>
            <button class="btn btn--primary" type="submit">Prisijungti</button>
        </form>

        <form class="card" method="post" action="/?action=register">
            <h2>Nauja paskyra</h2>
            <label class="field">
                <span>El. paštas</span>
                <input type="email" name="email" autocomplete="email" required>
            </label>
            <label class="field">
                <span>Slaptažodis</span>
                <input type="password" name="password" autocomplete="new-password" required minlength="6">
            </label>
            <label class="field">
                <span>Pakartokite slaptažodį</span>
                <input type="password" name="password_confirm" autocomplete="new-password" required minlength="6">
            </label>
            <button class="btn" type="submit">Sukurti paskyrą</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
