<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.html");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Créer un adhérent</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="icon" href="../../logo/idk.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <a href="change_password.html">Changer mon mot de passe</a>
    <h2>Créer un adhérent</h2>

    <form method="POST" action="create_user.php">
    <input type="email" name="email" required placeholder="Email de l'adhérent">
    <input type="password" name="password" required placeholder="Mot de passe provisoire">
    <button type="submit">Créer le compte</button>
    </form>

    <a href="logout.php">Déconnexion</a>
</body>
</html>