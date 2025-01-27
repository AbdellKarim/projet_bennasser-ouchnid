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


 <?php 
/*
    // Démarrer la session
    session_start();

    // Définir un message dynamique pour l'utilisateur connecté ou par défaut
    if (isset($_SESSION['pseudo'])) {
        $message_accueil = "Bienvenue, " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . " !";
    } else {
        $message_accueil = "Bienvenue sur le site Clicom. Veuillez vous connecter pour continuer.";
    }
*/
?>

 
    <header class="en_tete">
        <h1><a href="index.php">Bibliothèque</a></h1>
    </header>
    <nav class="menu">
        <ul>
            <li><a href="#">Abonnés</a>
                <ul>
                    <li><a href="../Clients/liste.php">Liste des clients</a></li>
                    <li><a href="../Clients/ajouter.php">Ajouter des clients</a></li>
                    <li><a href="../Clients/modifier.php">Modification</a></li>
                    <li><a href="../Clients/supprimer.php">Suppression</a></li>
                </ul>
            </li>
            <li><a href="#">Commandes</a>
                <ul>
                    <li><a href="../Commandes/liste.php">Liste</a></li>
                    <li><a href="../Commandes/ajouter.php">Ajouter</a></li>
                    <li><a href="../Commandes/modifier.php">Modification</a></li>
                    <li><a href="../Commandes/supprimer.php">Suppression</a></li>
                    <li><a href="../SCRIPTS/DB.php">Facture</a></li>
                </ul>
            </li>
            <li><a href="#">Produit</a>
                <ul>
                    <li><a href="../Produits/liste.php">Liste</a></li>
                    <li><a href="../Produits/ajouter.php">Ajouter des produits</a></li>
                    <li><a href="../Produits/modifier.php">Modification</a></li>
                    <li><a href="../Produits/supprimer.php">Suppression</a></li>
                </ul>
            </li>
            <li><a href="#">Connexion</a>
                <ul>
                    <li><a href="/projet_bennasser-ouchnid/UTILISATEURS/login.php">Login</a></li>
                    <li><a href="/projet_bennasser-ouchnid/UTILISATEURS/inscription.php">Inscription</a></li>
                    <li><a href="/projet_bennasser-ouchnid/UTILISATEURS/logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Message d'accueil -->
    <section class="message-accueil">
        <h1>Bienvenue, dans Notre Projet !</h1>
        <p>BenNasser, Ouchnid</p>
        <p>Ceci est votre page d'accueil après connexion.</p>
        <p><a href="/projet_bennasser-ouchnid/UTILISATEURS/logout.php">Se déconnecter</a></p>
    </section>

</body>
</html>
