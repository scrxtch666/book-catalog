<?php

session_start();

require_once __DIR__ . '/../../src/Database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password_hash'] ?? '';
// Vyhledání uživatele v databázi
$pdo = getPDO();
$stmt = $pdo->prepare(
    'SELECT * FROM admin_users WHERE username = ?'
);

$stmt->execute([$username]);

$user = $stmt->fetch();
// Ověření uživatele a hesla, případně invalid_credentials
if (!$user || !password_verify($password, $user['password_hash'])) {
    header('Location: ../login.html?error=invalid_credentials');
    exit;
}
// Úspěšné přihlášení
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

header('Location: ../admin/admin.php');
exit;
