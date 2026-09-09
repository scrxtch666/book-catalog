<?php

session_start();

require_once __DIR__ . '/../../src/Database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password_hash'] ?? '';

$pdo = getPDO();
$stmt = $pdo->prepare(
    'SELECT * FROM admin_users WHERE username = ?'
);

$stmt->execute([$username]);

$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    header('Location: ../login.html?error=invalid_credentials');
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

header('Location: ../admin/admin.php');
exit;
