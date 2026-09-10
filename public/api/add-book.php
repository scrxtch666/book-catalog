<?php

require_once '/var/www/src/Database.php';

$errors = [];
$values = ['title' => '', 'author' => '', 'year' => '', 'annotation' => '', 'rating' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $errors['year'] = 'Rok musí být platné číslo (1450–' . (date('Y')) . ').';
    }
    if ($values['rating'] !== '' && (!is_numeric($values['rating']) || $values['rating'] < 0 || $values['rating'] > 5)) {
        $errors['rating'] = 'Hodnocení musí být číslo 0–5.';
    }

    if (empty($errors)) {
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            'INSERT INTO books (title, author, year, annotation, rating) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $values['title'],
            $values['author'],
            (int) $values['year'],
            $values['annotation'] ?: null,
            $values['rating'] !== '' ? (float) $values['rating'] : null,
        ]);

        header('Location: /admin/admin.php?added=' . urlencode($values['title']));
        exit;
    }
}
?>