


<?php require 'login.html' ?>






<<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idutilisateur'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    
</head>
<body>
    <div class="container">
        <h1>Bienvenue,  dans Notre Projet  !</h1>
        <p>BenNasser , Ouchnid  </p>
        <p>Ceci est votre page d'accueil après connexion.</p>
        <p><a href="logout.php">Se déconnecter</a></p>
    </div>
</body>
</html>













