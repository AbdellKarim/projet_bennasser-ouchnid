<?php
// Vérifier et démarrer une session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idutilisateur'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: login.html');
    exit();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<body>
    <!-- Inclure le header -->
    <?php include 'header.php'; ?>

    <!-- Contenu spécifique à l'accueil -->
    <div style="text-align: center; margin-top: 50px;">
        <h1>Bienvenue dans votre espace personnel</h1>
        <p>  Utilisez le menu pour naviguer .</p>
    </div>
</body>
</html>
