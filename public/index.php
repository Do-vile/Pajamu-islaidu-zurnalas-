<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/view.php';
require_once __DIR__ . '/../src/entries.php';


start_session();
db();

$action = $_GET['action'] ?? null;

if ($action === 'logout') {
    logout_user();
    flash_set('success', 'Sėkmingai atsijungėte.');
    header('Location: /?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $error = login_user((string) ($_POST['email'] ?? ''), (string) ($_POST['password'] ?? ''));
        if ($error) {
            flash_set('error', $error);
            header('Location: /?page=login');
            exit;
        }

        flash_set('success', 'Sveiki sugrįžę!');
        header('Location: /?page=dashboard');
        exit;
    }

    if ($action === 'register') {
        $error = register_user(
            (string) ($_POST['email'] ?? ''),
            (string) ($_POST['password'] ?? ''),
            (string) ($_POST['password_confirm'] ?? '')
        );
        if ($error) {
            flash_set('error', $error);
            header('Location: /?page=login');
            exit;
        }

        flash_set('success', 'Paskyra sukurta!');
        header('Location: /?page=dashboard');
        exit;
    }

    $user = require_auth();

    if ($action === 'create_document') {
        $error = create_document_with_entry((int) $user['id'], $_POST);
        if ($error) {
            flash_set('error', $error);
            header('Location: /?page=documents');
            exit;
        }

        flash_set('success', 'Dokumentas išsaugotas.');
        header('Location: /?page=journal');
        exit;
    }

    if ($action === 'delete_entry') {
        $entryId = (int) ($_POST['entry_id'] ?? 0);
        delete_entry((int) $user['id'], $entryId);
        header('Location: /?page=journal');
        exit;
    }
}

$user = current_user();
$page = $_GET['page'] ?? 'dashboard';

if (!$user) {
    render('login', ['title' => 'Prisijungimas']);
    exit;
}

switch ($page) {
    case 'dashboard':
        render('dashboard', [
            'title' => 'Bendra',
            'totals' => totals_for_user((int) $user['id']),
            'recentIncome' => recent_entries_by_type((int) $user['id'], 'income'),
            'recentExpense' => recent_entries_by_type((int) $user['id'], 'expense'),
            'activePage' => 'dashboard'
        ]);
        break;

    case 'documents':
        render('documents', [
            'title' => 'Dokumentai',
            'today' => date('Y-m-d'),
            'activePage' => 'documents'
        ]);
        break;

    case 'journal':
        render('journal', [
            'title' => 'Žurnalas',
            'entries' => fetch_entries((int) $user['id'], null, null, null),
            'activePage' => 'journal'
        ]);
        break;

    case 'occupancy':
        render('occupancy', [
            'title' => 'Užimtumas',
            'activePage' => 'occupancy'
        ]);
        break;

    default:
        header('Location: /');
        exit;
}
