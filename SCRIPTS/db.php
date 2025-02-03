

<?php
// Connexion à la base de données
$connexion = new mysqli("localhost", "root", "", "clicom");
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}
mysqli_set_charset($connexion, "utf8");
?>