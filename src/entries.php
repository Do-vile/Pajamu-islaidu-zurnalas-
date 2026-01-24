<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function cents_from_amount(string $amount): int
{
    $normalized = str_replace(',', '.', trim($amount));
    if ($normalized === '' || !is_numeric($normalized)) {
        return 0;
    }

    return (int) round(((float) $normalized) * 100);
}

function format_eur(int $cents): string
{
    return number_format($cents / 100, 2, ',', ' ') . ' EUR';
}

function create_document_with_entry(int $userId, array $data): ?string
{
    $docDate = trim((string) ($data['doc_date'] ?? ''));
    $docNumber = trim((string) ($data['doc_number'] ?? ''));
    $client = trim((string) ($data['client'] ?? ''));
    $amount = trim((string) ($data['amount'] ?? ''));
    $entryType = (string) ($data['entry_type'] ?? 'income');

    if ($docDate === '' || $docNumber === '' || $client === '' || $amount === '') {
        return 'Užpildykite visus laukus.';
    }

    $amountCents = cents_from_amount($amount);
    if ($amountCents <= 0) {
        return 'Suma turi būti didesnė už 0.';
    }

    if (!in_array($entryType, ['income', 'expense'], true)) {
        return 'Neteisingas įrašo tipas.';
    }

    $pdo = db();
    $pdo->beginTransaction();

    try {
        $docStmt = $pdo->prepare(
            'INSERT INTO documents (user_id, doc_date, doc_number, client, amount_cents, entry_type)
             VALUES (:user_id, :doc_date, :doc_number, :client, :amount_cents, :entry_type)'
        );
        $docStmt->execute([
            ':user_id' => $userId,
            ':doc_date' => $docDate,
            ':doc_number' => $docNumber,
            ':client' => $client,
            ':amount_cents' => $amountCents,
            ':entry_type' => $entryType,
        ]);

        $documentId = (int) $pdo->lastInsertId();

        $entryStmt = $pdo->prepare(
            'INSERT INTO entries (user_id, document_id, entry_date, doc_number, client, amount_cents, entry_type)
             VALUES (:user_id, :document_id, :entry_date, :doc_number, :client, :amount_cents, :entry_type)'
        );
        $entryStmt->execute([
            ':user_id' => $userId,
            ':document_id' => $documentId,
            ':entry_date' => $docDate,
            ':doc_number' => $docNumber,
            ':client' => $client,
            ':amount_cents' => $amountCents,
            ':entry_type' => $entryType,
        ]);

        $pdo->commit();

        return null;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return 'Nepavyko išsaugoti dokumento.';
    }
}

function fetch_entries(int $userId, ?string $fromDate, ?string $toDate, ?string $entryType): array
{
    $conditions = ['user_id = :user_id'];
    $params = [':user_id' => $userId];

    if ($fromDate) {
        $conditions[] = 'entry_date >= :from_date';
        $params[':from_date'] = $fromDate;
    }

    if ($toDate) {
        $conditions[] = 'entry_date <= :to_date';
        $params[':to_date'] = $toDate;
    }

    if ($entryType && in_array($entryType, ['income', 'expense'], true)) {
        $conditions[] = 'entry_type = :entry_type';
        $params[':entry_type'] = $entryType;
    }

    $where = implode(' AND ', $conditions);

    $stmt = db()->prepare(
        "SELECT id, entry_date, doc_number, client, amount_cents, entry_type, created_at
         FROM entries
         WHERE {$where}
         ORDER BY entry_date DESC, id DESC"
    );
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function delete_entry(int $userId, int $entryId): bool
{
    $stmt = db()->prepare('DELETE FROM entries WHERE id = :id AND user_id = :user_id');
    $stmt->execute([
        ':id' => $entryId,
        ':user_id' => $userId,
    ]);

    return $stmt->rowCount() > 0;
}

function totals_for_user(int $userId): array
{
    $stmt = db()->prepare(
        'SELECT
            COALESCE(SUM(CASE WHEN entry_type = "income" THEN amount_cents END), 0) AS income_cents,
            COALESCE(SUM(CASE WHEN entry_type = "expense" THEN amount_cents END), 0) AS expense_cents
         FROM entries
         WHERE user_id = :user_id'
    );
    $stmt->execute([':user_id' => $userId]);
    $row = $stmt->fetch() ?: ['income_cents' => 0, 'expense_cents' => 0];

    $income = (int) $row['income_cents'];
    $expense = (int) $row['expense_cents'];

    return [
        'income_cents' => $income,
        'expense_cents' => $expense,
        'taxable_cents' => $income - $expense,
    ];
}

function recent_entries_by_type(int $userId, string $entryType, int $limit = 5): array
{
    if (!in_array($entryType, ['income', 'expense'], true)) {
        return [];
    }

    $stmt = db()->prepare(
        'SELECT id, entry_date, doc_number, client, amount_cents, entry_type
         FROM entries
         WHERE user_id = :user_id AND entry_type = :entry_type
         ORDER BY entry_date DESC, id DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':entry_type', $entryType, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

