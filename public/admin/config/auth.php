<?php
// Zkontroluje user id ze session, pokud není, tak směruje na login page
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}
