<?php
require_once __DIR__ . '/../admin/config/auth.php';
require_once '/var/www/src/Database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/admin.php');
    exit;
}

$errors = [];
$values = ['title' => '', 'author' => '', 'year' => '', 'annotation' => '', 'rating' => ''];

$values['title']      = trim($_POST['title'] ?? '');
$values['author']     = trim($_POST['author'] ?? '');
$values['year']       = trim($_POST['year'] ?? '');
$values['annotation'] = trim($_POST['annotation'] ?? '');
$values['rating']     = trim($_POST['rating'] ?? '');

if ($values['title'] === '') {
    $errors['title'] = 'Zadejte název knihy.';
}

if ($values['author'] === '') {
    $errors['author'] = 'Zadejte autora.';
}

if ($values['year'] === '') {
    $errors['year'] = 'Zadejte rok vydání.';
} elseif (!ctype_digit($values['year']) || (int)$values['year'] < 1450 || (int)$values['year'] > (int)date('Y') + 1) {
    $errors['year'] = 'Rok musí být platné číslo (1450–' . date('Y') . ').';
}

if ($values['annotation'] === '') {
    $errors['annotation'] = 'Zadejte anotaci.';
}

if ($values['rating'] === '') {
    $errors['rating'] = 'Zadejte hodnocení.';
} elseif (!is_numeric($values['rating']) || $values['rating'] < 0 || $values['rating'] > 5) {
    $errors['rating'] = 'Hodnocení musí být číslo 0–5.';
}

if (!empty($errors)) {
    $_SESSION['add_book_errors'] = $errors;
    $_SESSION['add_book_values'] = $values;
    header('Location: /admin/admin.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare(
    'INSERT INTO books (title, author, year, annotation, rating) VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([
    $values['title'],
    $values['author'],
    (int) $values['year'],
    $values['annotation'],
    (float) $values['rating'],
]);

header('Location: /admin/admin.php?added=' . urlencode($values['title']));
exit;
