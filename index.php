

<?php 

session_start();// Démarrer

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idutilisateur'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: login.php');
    exit();
}
    
?>
<?php require 'header.php' ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, dans Notre Projet !</h1>
        <p>BenNasser, Ouchnid</p>

        <p><a href="./UTILISATEURS/logout.php">Se déconnecter</a></p>
    </div>
</body>
</html>



<style>
        .container {
            margin: 50px auto;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .container h1 {
            color: #007bff;
        }
        .container p {
            margin: 10px 0;
        }
        .container a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .container a:hover {
            text-decoration: underline;
        }
    </style>














