<?php
require '../auth.php'; // Vérifier si l'utilisateur est connecté
require '../header.php'; // Inclusion du header

// **********************************************
// Initialisation des messages
$message = "";
$message_erreur = "";

// **********************************************
// Connexion à la base de données
$connexion = mysqli_connect("localhost", "root", "", "clicom");

if ($connexion) {
    mysqli_set_charset($connexion, "utf8");
} else {
    $message_erreur .= "Erreur de connexion à la base de données<br>\n";
    $message_erreur .= "Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
}

// **********************************************
// Récupération des produits
if (empty($message_erreur)) {
    $requete = "SELECT * FROM produit";
    $resultat = mysqli_query($connexion, $requete);

    if ($resultat) {
        if (mysqli_num_rows($resultat) == 0) {
            $message .= "Aucun produit trouvé<br>\n";
        } else {
            // Construction du tableau
            $tableau_produits = "<table>\n<thead>\n<tr>
                                    <th>ID Produit</th>
                                    <th>Libellé</th> 
                                    <th>Prix HT</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>\n</thead>\n<tbody>\n";

            while ($ligne = mysqli_fetch_assoc($resultat)) {
                $tableau_produits .= "<tr>
                                        <td>" . htmlspecialchars($ligne['NPro']) . "</td>
                                        <td>" . htmlspecialchars($ligne['Libelle']) . "</td>
                                        <td>" . htmlspecialchars($ligne['PrixHT']) . " €</td>
                                        <td>" . htmlspecialchars($ligne['QStock']) . "</td>
                                        <td class='actions'>
                                            <a href='modifier.php?id=" . $ligne['NPro'] . "' class='edit'>Modifier</a>
                                            <a href='supprimer.php?id=" . $ligne['NPro'] . "' class='delete' onclick='return confirm(\"Voulez-vous vraiment supprimer ce produit ?\");'>Supprimer</a>
                                        </td>
                                    </tr>\n";
            }

            $tableau_produits .= "</tbody>\n</table>\n";
        }
    } else {
        $message_erreur .= "Erreur de la requête : $requete<br>\n";
        $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }
}

// **********************************************
// Déconnexion de la base de données
if ($connexion) {
    mysqli_close($connexion);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Produits</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
        /* Style du tableau */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 14px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        th {
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        /* Actions */
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            color: white;
            border-radius: 3px;
        }
        .edit {
            background-color: #28a745;
        }
        .delete {
            background-color: #dc3545;
        }
        .edit:hover {
            background-color: #218838;
        }
        .delete:hover {
            background-color: #c82333;
        }
        /* Bouton Ajouter */
        .add-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .add-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Liste des Produits</h1>
    </header>

    <main>
        <a href="ajouter.php" class="add-btn">Ajouter un Produit</a>

        <!-- Affichage des messages -->
        <?php if (!empty($message_erreur) || !empty($message)) { ?>
            <section>
                <h2>Logs</h2>
                <?php
                if (!empty($message_erreur)) {
                    echo "<section style='color: red;'>\n" . $message_erreur . "</section>\n";
                }
                if (!empty($message)) {
                    echo "<section style='color: green;'>\n" . $message . "</section>\n";
                }
                ?>
            </section>
        <?php } ?>

        <!-- Affichage du tableau -->
        <?php if (!empty($tableau_produits)) { ?>
            <section>
                <?php echo $tableau_produits; ?>
            </section>
        <?php } ?>
    </main>

    <?php require '../fotter.php'; ?>
</body>
</html>
