<?php

require_once __DIR__ . '/../../src/database.php';

$pdo = getPDO();

header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;

if ($id !== null) {
    $stmt = $pdo->prepare("
        SELECT *
        FROM books
        WHERE id = ?
    ");
    $stmt->execute([(int)$id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        http_response_code(404);
        echo json_encode(['error' => 'Kniha nebyla nalezena'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode($book, JSON_UNESCAPED_UNICODE);
} else {
    $stmt = $pdo->query("
        SELECT *
        FROM books
    ");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($books, JSON_UNESCAPED_UNICODE);
}
