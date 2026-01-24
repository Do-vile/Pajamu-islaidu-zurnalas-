<?php declare(strict_types=1); ?>
<?php require __DIR__ . '/partials/header.php'; ?>

<section class="panel">
    <header class="panel-header panel-header--with-action">
        <div>
            <h1>Žurnalas</h1>
            <p>Visi pajamų ir sąnaudų įrašai su filtrais.</p>
        </div>
    </header>

    <form class="card filters" method="get" action="/">
        <input type="hidden" name="page" value="journal">
        <div class="filters-grid">
            <label class="field">
                <span>Nuo</span>
                <input type="date" name="from" value="<?= htmlspecialchars($filters['from'] ?? '', ENT_QUOTES) ?>">
            </label>
            <label class="field">
                <span>Iki</span>
                <input type="date" name="to" value="<?= htmlspecialchars($filters['to'] ?? '', ENT_QUOTES) ?>">
            </label>
            <label class="field">
                <span>Tipas</span>
                <select name="type">
                    <option value="">Visi</option>
                    <option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : '' ?>>Pajamos</option>
                    <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Sąnaudos</option>
                </select>
            </label>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Filtruoti</button>
            <a class="btn btn--ghost" href="/?page=journal">Išvalyti</a>
        </div>
    </form>

    <div class="card table-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Dokumentas</th>
                    <th>Klientas / tiekėjas</th>
                    <th>Tipas</th>
                    <th class="align-right">Suma</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($entries)): ?>
                    <tr><td colspan="6" class="empty">Įrašų nerasta.</td></tr>
                <?php else: ?>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry['entry_date'], ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($entry['doc_number'], ENT_QUOTES) ?></td>
                            <td><?= htmlspecialchars($entry['client'], ENT_QUOTES) ?></td>
                            <td>
                                <span class="tag <?= $entry['entry_type'] === 'income' ? 'tag--income' : 'tag--expense' ?>">
                                    <?= $entry['entry_type'] === 'income' ? 'Pajamos' : 'Sąnaudos' ?>
                                </span>
                            </td>
                            <td class="align-right"><?= htmlspecialchars(format_eur((int) $entry['amount_cents']), ENT_QUOTES) ?></td>
                            <td class="align-right">
                                <form method="post" action="/?action=delete_entry" class="inline-form" data-confirm="Ar tikrai norite ištrinti įrašą?">
                                    <input type="hidden" name="entry_id" value="<?= (int) $entry['id'] ?>">
                                    <button class="btn btn--danger" type="submit">Trinti</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm') || 'Ar tikrai?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>
