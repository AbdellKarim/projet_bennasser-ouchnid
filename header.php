<!doctype html>
<html lang="fr">

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
         <h1><a href="../accueil.php">Bibliothèque</a></h1>
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

    

</body>
</html>
