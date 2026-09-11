<?php
require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../admin/config/auth.php';
$jsonPath = __DIR__ . '/../../src/data/books.json';

if (!file_exists($jsonPath)) {
    header('Location: /admin/admin.php?import=' . urlencode('Soubor books.json nebyl nalezen!'));
    exit;
}

$books = json_decode(file_get_contents($jsonPath), true);

if (!is_array($books)) {
    header('Location: /admin.php?import=' . urlencode('Soubor books.json má neplatný formát.'));
    exit;
}

$pdo = getPDO();
$imported = 0;
$skipped = 0;

try {
    $pdo->beginTransaction();

    $insertStmt = $pdo->prepare('INSERT INTO books (title, author, year, annotation, rating) VALUES (?, ?, ?, ?, ?)');
    $checkStmt = $pdo->prepare('SELECT id FROM books WHERE title = ? AND author = ?');

    foreach ($books as $item) {
        $title      = $item['title'] ?? null;
        $author     = $item['author'] ?? null;
        $year       = $item['year'] ?? null;
        $annotation = $item['annotation'] ?? null;
        $rating     = $item['rating'] ?? null;

        if (!$title || !$author) {
            continue;
        }

        $checkStmt->execute([$title, $author]);
        if ($checkStmt->fetch()) {
            $skipped++;
            continue;
        }

        $insertStmt->execute([$title, $author, $year, $annotation, $rating]);
        $imported++;
    }

    $pdo->commit();
    $message = "Naimportováno $imported knih, přeskočeno $skipped duplicit.";
} catch (Exception $e) {
    $pdo->rollBack();
    $message = 'Import selhal: ' . $e->getMessage();
}

header('Location: /admin/admin.php?import=' . urlencode($message));
exit;
