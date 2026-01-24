<?php declare(strict_types=1); ?>
<?php require __DIR__ . '/partials/header.php'; ?>

<section class="panel">
    <header class="panel-header panel-header--with-action">
        <div>
            <h1>Dokumentai</h1>
            <p>Sukurkite dokumentą, kuris automatiškai pateks į žurnalą.</p>
        </div>
    </header>

    <form class="card form" method="post" action="/?action=create_document">
        <div class="form-grid">
            <label class="field">
                <span>Data</span>
                <input type="date" name="doc_date" value="<?= htmlspecialchars($today, ENT_QUOTES) ?>" required>
            </label>
            <label class="field">
                <span>Numeris</span>
                <input type="text" name="doc_number" placeholder="PVZ-001" required>
            </label>
            <label class="field">
                <span>Klientas / tiekėjas</span>
                <input type="text" name="client" placeholder="UAB Pavyzdys" required>
            </label>
            <label class="field">
                <span>Suma (EUR)</span>
                <input type="number" name="amount" min="0.01" step="0.01" placeholder="100.00" required>
            </label>
            <label class="field">
                <span>Tipas</span>
                <select name="entry_type" required>
                    <option value="income">Pajamos</option>
                    <option value="expense">Sąnaudos</option>
                </select>
            </label>
        </div>
        <div class="form-actions">
            <button class="btn btn--primary" type="submit">Išsaugoti dokumentą</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
