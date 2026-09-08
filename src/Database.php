<?php
function getPDO(): PDO {
    $host = getenv('DB_HOST') ?: 'db';
    $db   = getenv('DB_NAME') ?: 'books';
    $user = getenv('DB_USER') ?: 'books_user';
    $pass = getenv('DB_PASSWORD') ?: 'secret';

    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}