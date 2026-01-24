<?php declare(strict_types=1); ?>
<?php require __DIR__ . '/partials/header.php'; ?>

<section class="panel">
    <header class="panel-header">
        <h1>Mėnesio įrašai</h1>
    </header>

    <div class="split">
        <article class="split-card">
            <div class="split-card__header">
                <h2>Pajamos</h2>
                <div class="pill pill--income"><?= htmlspecialchars(format_eur($totals['income_cents']), ENT_QUOTES) ?></div>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Operacija</th>
                        <th>Kategorija</th>
                        <th class="align-right">Suma</th>
                        <th>Būsena</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($recentIncome)): ?>
                        <tr><td colspan="5" class="empty">Dar nėra pajamų įrašų.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentIncome as $entry): ?>
                            <tr>
                                <td><?= htmlspecialchars($entry['entry_date'], ENT_QUOTES) ?></td>
                                <td>
                                    <strong>Sąskaita <?= htmlspecialchars($entry['doc_number'], ENT_QUOTES) ?></strong>
                                    <div class="muted"><?= htmlspecialchars($entry['client'], ENT_QUOTES) ?></div>
                                </td>
                                <td>Pajamos</td>
                                <td class="align-right"><?= htmlspecialchars(format_eur((int) $entry['amount_cents']), ENT_QUOTES) ?></td>
                                <td><span class="badge">Perkelta</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>

        <article class="split-card">
            <div class="split-card__header">
                <h2>Išlaidos</h2>
                <div class="pill pill--expense"><?= htmlspecialchars(format_eur($totals['expense_cents']), ENT_QUOTES) ?></div>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Operacija</th>
                        <th>Kategorija</th>
                        <th class="align-right">Suma</th>
                        <th>Būsena</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($recentExpense)): ?>
                        <tr><td colspan="5" class="empty">Dar nėra išlaidų įrašų.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentExpense as $entry): ?>
                            <tr>
                                <td><?= htmlspecialchars($entry['entry_date'], ENT_QUOTES) ?></td>
                                <td>
                                    <strong>Išlaida <?= htmlspecialchars($entry['doc_number'], ENT_QUOTES) ?></strong>
                                    <div class="muted"><?= htmlspecialchars($entry['client'], ENT_QUOTES) ?></div>
                                </td>
                                <td>Sąnaudos</td>
                                <td class="align-right"><?= htmlspecialchars(format_eur((int) $entry['amount_cents']), ENT_QUOTES) ?></td>
                                <td><span class="badge">Perkelta</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </div>

    <section class="summary">
        <div class="summary-card">
            <h3>Metinė suvestinė (pasirinktiems metams)</h3>
            <p>Taikomos faktinės išlaidos (rankinis suvedimas).</p>
        </div>
        <div class="summary-pills">
            <div class="pill"><?= 'Pajamos: ' . htmlspecialchars(format_eur($totals['income_cents']), ENT_QUOTES) ?></div>
            <div class="pill"><?= 'Išlaidos: ' . htmlspecialchars(format_eur($totals['expense_cents']), ENT_QUOTES) ?></div>
            <div class="pill pill--taxable"><?= 'Apmokestinama bazė: ' . htmlspecialchars(format_eur($totals['taxable_cents']), ENT_QUOTES) ?></div>
        </div>
    </section>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
