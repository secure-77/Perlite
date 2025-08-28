<?php
ini_set('session.save_path', '/tmp');
ini_set('session.name', 'PERLITE_AUTH');
session_start();

$USER = getenv('PERLITE_USERNAME') ?: 'admin';
$PASS = getenv('PERLITE_PASSWORD') ?: 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if ($user === $USER && $pass === $PASS) {
        $_SESSION["auth"] = true;
        session_regenerate_id(true);
        header("Location: /");
        exit;
    }
}

header("Location: /auth/login.php?error=1");
exit;
