<!doctype html>
<html lang="fr">
    

<head>
    <meta charset="UTF-8">
    <title>Clicom</title>
    <link rel="stylesheet" href=<?php __DIR__ . '/CSS/header.css' ?>> 
    </head>

<body>




 
    <header class="en_tete">
    <h1><a href="index.php">Bibliothèque</a></h1>
    </header>
    <nav class="menu">
        <ul>
            <li><a href="#">Abonnés</a>
                <ul>

              <?php  define('BASE_URL',  "/projet_bennasser-ouchnid/"); ?>
                    <li><a href="./Clients/liste.php">Liste des clients</a></li>
                    <li><a href= './Clients/ajouter.php'  >Ajouter des clients</a></li>
                    <li><a href= './Clients/modifier.php'>Modification</a></li>
                    <li><a href= './Clients/supprimer.php'>Suppression</a></li>
                </ul>
            </li>
            <li><a href="#">Commandes</a>
                <ul>
                    <li><a href= './Commandes/liste.php' >Liste</a></li>
                    <li><a href= './Commandes/ajouter.php'   >Ajouter</a></li>
                    <li><a href= './Commandes/modifier.php'   >Modification</a></li>
                    <li><a href= './Commandes/supprimer.php'   >Suppression</a></li>
                    <li><a href= './SCRIPTS/DB.php'   >Facture</a></li>
                </ul>
            </li>
            <li><a href="#">Produit</a>
                <ul>
                    <li><a href='./Produits/liste.php'   >Liste</a></li>
                    <li><a href='./Produits/ajouter.php'   >Ajouter des produits</a></li>
                    <li><a href='./Produits/modifier.php'   >Modification</a></li>
                    <li><a href='./Produits/supprimer.php'   >Suppression</a></li>
                </ul>
            </li>
            <li><a href="#">Connexion</a>
            

            </li>
        </ul>
    </nav>

    

</body>
</html>

<style>
/* Style général */
html {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Fond léger */
    margin: 0;
    padding: 0;
}

/* ---------- HEADER ----------- */
.en_tete {
    background: url('../IMAGES/bibliotheque.jpg') no-repeat center/cover;
    text-align: center;
    padding: 50px 0;
    color: white;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
}

.en_tete h1 {
    font-size: 4em;
    margin: 0;
}

.en_tete a {
    text-decoration: none;
    color: white;
}

/* ---------- MENU NAVIGATION ----------- */
.menu {
    background-color: #222;
    height: 3.5em;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
}

.menu > ul {
    padding: 0;
    margin: 0;
    list-style: none;
    display: flex;
}

.menu > ul > li {
    position: relative;
    width: 10em;
    text-align: center;
    padding: 10px 0;
}

.menu > ul > li > a {
    text-decoration: none;
    color: azure;
    font-weight: bold;
    display: block;
    padding: 10px;
    transition: background 0.3s;
}

.menu > ul > li:hover {
    background: #444;
}

/* Sous-menus */
.menu ul li ul {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background: #333;
    width: 100%;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    padding: 0;
}

.menu ul li:hover ul {
    display: block;
}

.menu ul li ul li {
    list-style: none;
    padding: 10px;
}

.menu ul li ul li a {
    color: white;
    display: block;
    padding: 10px;
    transition: background 0.3s;
}

.menu ul li ul li a:hover {
    background: #555;
}

/* ---------- SECTION ACCUEIL ----------- */
.accueil {
    border: 1px solid #ccc;
    border-radius: 10px;
    background: white;
    width: 80%;
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    text-align: center;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

/* ---------- PIED DE PAGE ----------- */
.piedDePage {
    text-align: center;
    padding: 15px;
    background: #222;
    color: white;
    margin-top: 20px;
}

.piedDePage a {
    color: #ffcc00;
    text-decoration: none;
}

.piedDePage a:hover {
    text-decoration: underline;
}

/* ---------- RESPONSIVE DESIGN (Mobile) ----------- */
@media screen and (max-width: 768px) {
    .en_tete h1 {
        font-size: 3em;
    }

    .menu {
        flex-direction: column;
        height: auto;
    }

    .menu > ul {
        flex-direction: column;
    }

    .menu > ul > li {
        width: 100%;
    }

    .accueil {
        width: 90%;
        padding: 15px;
    }
}
</style>