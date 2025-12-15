<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit;
}
?>

<h1>Espace adhérent</h1>

<ul>
  <li><a href="change_password.html">Changer mon mot de passe</a></li>
  <li><a href="logout.php">Déconnexion</a></li>
</ul>

<?php if ($_SESSION['role'] === 'admin'): ?>
  <hr>
  <h2>Administration</h2>
  <a href="admin.php">Gestion des adhérents</a>
<?php endif; ?>
