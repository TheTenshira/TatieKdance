<?php
require "db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: login.html");
  exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
  die("Identifiants incorrects");
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'admin') {
  header("Location: dashboard.php");
} else {
  header("Location: dashboard.php");
}
exit;

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="icon" href="../../logo/idk.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="login-container">
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form method="POST" action="login.php">
                <input type="email" name="email" required placeholder="Email">
                <input type="password" name="password" required placeholder="Mot de passe">
                <button type="submit">Connexion</button>
            </form>
        </div>
    </div>
</body>
</html>