<?php
require "db.php";
session_start();

// Sécurité absolue
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  die("Accès interdit");
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  die("Email invalide");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
  "INSERT INTO users (email, password, role) VALUES (?, ?, 'member')"
);

try {
  $stmt->execute([$email, $hash]);
  echo "Compte créé avec succès";
} catch (PDOException $e) {
  die("Cet email existe déjà");
}
?>