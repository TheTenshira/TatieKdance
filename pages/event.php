<?php
session_start();
// Supposons que $_SESSION['is_admin'] est défini à true pour l'administrateur
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
    <h2>Ajouter une photo d'événement</h2>
    <form action="upload_photo.php" method="post" enctype="multipart/form-data">
        <label for="photo">Choisir une photo :</label>
        <input type="file" name="photo" id="photo" accept="image/*" required>
        <button type="submit">Poster la photo</button>
    </form>
<?php else: ?>
    <p></p>
<?php endif; ?>
