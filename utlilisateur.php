<?php
$connexion = new mysqli("localhost", "root", "", "clicom");
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}

// Ajouter un utilisateur
$pseudo = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT); // Hasher le mot de passe
$nom = 'Admin';
$prenom = 'Superuser';

$requete = $connexion->prepare("INSERT INTO utilisateur (Pseudo, Password, Nom, Prenom) VALUES (?, ?, ?, ?)");
$requete->bind_param("ssss", $pseudo, $password, $nom, $prenom);

if ($requete->execute()) {
    echo "Utilisateur ajouté avec succès.";
} else {
    echo "Erreur : " . $connexion->error;
}

$requete->close();
$connexion->close();
?>

