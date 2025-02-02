<?php
require '../auth.php'; // Vérifie si l'utilisateur est connecté
require '../header.php'; // Inclusion du header

// **********************************************
// Initialisation des messages
$message = "";
$message_erreur = "";

// **********************************************
// Connexion à la base de données
$connexion = mysqli_connect("localhost", "root", "", "clicom");

if (!$connexion) {
    $message_erreur .= "Erreur de connexion à la base de données<br>\n";
    $message_erreur .= "Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
} else {
    mysqli_set_charset($connexion, "utf8");
}

// **********************************************
// Traitement du formulaire
$NPro = "";
$Libelle = "";
$PrixHT = "";
$QStock = "";

if (isset($_POST['ajouter'])) {
    $NPro = trim(htmlspecialchars($_POST['NPro']));
    $Libelle = trim(htmlspecialchars($_POST['Libelle']));
    $PrixHT = trim(htmlspecialchars($_POST['PrixHT']));
    $QStock = trim(htmlspecialchars($_POST['QStock']));

    // Vérifications
    if (empty($NPro)) {
        $message_erreur .= "Le champ ID Produit est obligatoire<br>\n";
    }
    if (empty($Libelle)) {
        $message_erreur .= "Le champ Libellé est obligatoire<br>\n";
    }
    if (empty($PrixHT) || !is_numeric($PrixHT) || $PrixHT < 0) {
        $message_erreur .= "Le Prix HT doit être un nombre positif<br>\n";
    }
    if (empty($QStock) || !is_numeric($QStock) || $QStock < 0) {
        $message_erreur .= "Le Stock doit être un nombre positif<br>\n";
    }

    // Si aucun message d'erreur
    if (empty($message_erreur)) {
        // Vérification si le produit existe déjà
        $requete = "SELECT * FROM produit WHERE NPro = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "s", $NPro);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultat) != 0) {
            $message_erreur .= "Le produit avec l'ID $NPro existe déjà<br>\n";
        } else {
            // Insertion du produit
            $requete = "INSERT INTO produit (NPro, Libelle, PrixHT, QStock) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($connexion, $requete);
            mysqli_stmt_bind_param($stmt, "ssdi", $NPro, $Libelle, $PrixHT, $QStock);
            $execution = mysqli_stmt_execute($stmt);

            if ($execution) {
                $message .= "Produit ajouté avec succès !<br>\n";
                // Réinitialiser les champs du formulaire après succès
                $NPro = "";
                $Libelle = "";
                $PrixHT = "";
                $QStock = "";
            } else {
                $message_erreur .= "Erreur lors de l'ajout du produit.<br>\n";
            }
        }
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
    <title>Ajouter un Produit</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
        /* Style du formulaire */
        .form-container {
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        .form-container h2 {
            text-align: center;
            color: #007bff;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .message {
            text-align: center;
            font-size: 16px;
            margin-bottom: 15px;
            color: green;
        }
        .message-erreur {
            text-align: center;
            font-size: 16px;
            margin-bottom: 15px;
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <h1>Ajouter un Produit</h1>
    </header>

    <main>
        <div class="form-container">
            <h2>Formulaire d'Ajout</h2>

            <?php if (!empty($message_erreur)) { ?>
                <p class="message-erreur"><?php echo $message_erreur; ?></p>
            <?php } ?>

            <?php if (!empty($message)) { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>

            <form action="" method="POST">
                <label for="NPro">ID Produit :</label>
                <input type="text" id="NPro" name="NPro" placeholder="Ex: PROD123" value="<?php echo $NPro; ?>" required>

                <label for="Libelle">Libellé :</label>
                <input type="text" id="Libelle" name="Libelle" placeholder="Nom du produit" value="<?php echo $Libelle; ?>" required>

                <label for="PrixHT">Prix HT (€) :</label>
                <input type="number" id="PrixHT" name="PrixHT" placeholder="Ex: 49.99" step="0.01" value="<?php echo $PrixHT; ?>" required>

                <label for="QStock">Stock :</label>
                <input type="number" id="QStock" name="QStock" placeholder="Quantité en stock" value="<?php echo $QStock; ?>" required>

                <input type="submit" name="ajouter" value="Ajouter le Produit">
            </form>
        </div>
    </main>

    <?php require '../fotter.php'; ?>
</body>
</html>
