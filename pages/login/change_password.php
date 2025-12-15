<?php
require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}

$old = $_POST['old_password'];
$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

if ($new !== $confirm) {
  die("Les mots de passe ne correspondent pas");
}

$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !password_verify($old, $user['password'])) {
  die("Ancien mot de passe incorrect");
}

$newHash = password_hash($new, PASSWORD_DEFAULT);

$update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->execute([$newHash, $_SESSION['user_id']]);

echo "Mot de passe modifié avec succès";
?>