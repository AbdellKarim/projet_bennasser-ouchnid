

<!doctype html>
<html lang="fr">
    
<?php

define('URL', '/projet_bennasser-ouchnid/');
?>
<head>
    <meta charset="UTF-8">
    <title>Clicom</title>
    <link rel="stylesheet" href="<?= URL ?>CSS/header.css"> 
    </head>

<body>


 


<header class="en_tete">
    <h1><a href="<?= URL ?>index.php">Bibliothèque</a></h1>
</header>

<nav class="menu">
    <ul>
        <li><a href="">Abonnés</a>
            <ul>
                <li><a href="<?= URL ?>Clients/liste.php">Liste des clients</a></li>
                <li><a href="<?= URL ?>Clients/ajouter.php">Ajouter des clients</a></li>
                <li><a href="<?= URL ?>Clients/modifier.php">Modification</a></li>
                <li><a href="<?= URL ?>Clients/supprimer.php">Suppression</a></li>
            </ul>
        </li>
        <li><a href="">Commandes</a>
            <ul>
                <li><a href="<?= URL ?>Commandes/liste.php">Liste</a></li>
                <li><a href="<?= URL ?>Commandes/ajouter.php">Ajouter</a></li>
                <li><a href="<?= URL ?>Commandes/modifier.php">Modification</a></li>
                <li><a href="<?= URL ?>Commandes/supprimer.php">Suppression</a></li>
                <li><a href="<?= URL ?>Commandes/DB.php">Facture</a></li>
            </ul>
        </li>
        <li><a href="">Produit</a>
            <ul>
                <li><a href="<?= URL ?>Produits/liste.php">Liste</a></li>
                <li><a href="<?= URL ?>Produits/ajouter.php">Ajouter des produits</a></li>
                <li><a href="<?= URL ?>Produits/modifier.php">Modification</a></li>
                <li><a href="<?= URL ?>Produits/supprimer.php">Suppression</a></li>
            </ul>
        </li>
        <li><a href="#">Connexion</a></li>
    </ul>
</nav>


            </li>
        </ul>
    </nav>

    

</body>
</html>

