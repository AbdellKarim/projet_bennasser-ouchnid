

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
    <title>Projet Clicom</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
        .message-accueil {
            margin: 30px auto;
            text-align: center;
            font-size: 1.2rem;
            color: #333;
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 80%;
        }
        .message-accueil h1 {
            font-size: 2rem;
            color: #007bff;
        }
        .message-accueil p {
            margin: 10px 0;
        }
        .message-accueil a {
            text-decoration: none;
            color: #ff0000;
            font-weight: bold;
        }
        .message-accueil a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>


    <!-- Message d'accueil -->
    <section class="message-accueil">

        <p>BenNasser, Ouchnid</p>

        <p><a href="../logout.php">Se déconnecter</a></p>
    </section>
</body>
</html>
