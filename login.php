<?php
session_start();

// Configuration de l'admin
$admin_username = 'admin';
$admin_password = 'admin123';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['is_admin'] = true;
        header("Location: index1.php");
        exit;
    } else {
        $_SESSION['is_admin'] = false;
        header("Location: index1.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <form method="POST">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Se connecter">
    </form>
</body>

</html>