<?php
ini_set('session.save_path', '/tmp');
ini_set('session.name', 'PERLITE_AUTH');
session_start();

if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
    header('Location: /');
    exit;
}

$error = '';
if (isset($_GET['error'])) {
    $error = "Invalid login details";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/auth/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (!empty($error)) echo "<p style='color:red;text-align:center;'>$error</p>"; ?>
        <form action="/auth/check.php" method="post">
            <label>Username: <input type="text" name="username" required></label><br>
            <label>Password: <input type="password" name="password" required></label><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
