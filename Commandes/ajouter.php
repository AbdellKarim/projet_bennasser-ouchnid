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
$NCom = "";
$Ncli = "";
$DateCom = "";

if (isset($_POST['ajouter'])) {
    $NCom = trim(htmlspecialchars($_POST['NCom']));
    $Ncli = trim(htmlspecialchars($_POST['Ncli']));
    $DateCom = $_POST['DateCom'];

    // Vérifications
    if (empty($NCom)) {
        $message_erreur .= "Le champ Numéro de Commande est obligatoire<br>\n";
    }
    if (empty($Ncli)) {
        $message_erreur .= "Le champ Client est obligatoire<br>\n";
    }
    if (empty($DateCom)) {
        $message_erreur .= "Le champ Date de Commande est obligatoire<br>\n";
    }

    // Si aucun message d'erreur
    if (empty($message_erreur)) {
        // Vérification si la commande existe déjà
        $requete = "SELECT * FROM commande WHERE NCom = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "s", $NCom);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultat) != 0) {
            $message_erreur .= "Une commande avec le numéro $NCom existe déjà<br>\n";
        } else {
            // Insertion de la commande
            $requete = "INSERT INTO commande (NCom, NCli, DateCom) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($connexion, $requete);
            mysqli_stmt_bind_param($stmt, "sss", $NCom, $Ncli, $DateCom);
            $execution = mysqli_stmt_execute($stmt);

            if ($execution) {
                $message .= "Commande ajoutée avec succès !<br>\n";
                // Réinitialiser les champs après succès
                $NCom = "";
                $Ncli = "";
                $DateCom = "";
            } else {
                $message_erreur .= "Erreur lors de l'ajout de la commande.<br>\n";
            }
        }
    }
}

// **********************************************
// Récupération de la liste des clients pour le menu déroulant
$clients_options = "";
$sql = "SELECT NCli FROM client";
$result = mysqli_query($connexion, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $clients_options .= "<option value='" . htmlspecialchars($row['NCli']) . "'>" . htmlspecialchars($row['NCli']) . "</option>\n";
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
    <title>Ajouter une Commande</title>
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
        input[type="text"], input[type="date"], select {
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
        <h1>Ajouter une Commande</h1>
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
                <label for="NCom">Numéro de Commande :</label>
                <input type="text" id="NCom" name="NCom" placeholder="Ex: 12345" value="<?php echo $NCom; ?>" required>

                <label for="Ncli">Client :</label>
                <select id="Ncli" name="Ncli" required>
                    <option value="">Sélectionnez un client</option>
                    <?php echo $clients_options; ?>
                </select>

                <label for="DateCom">Date de Commande :</label>
                <input type="date" id="DateCom" name="DateCom" value="<?php echo $DateCom; ?>" required>

                <input type="submit" name="ajouter" value="Ajouter la Commande">
            </form>
        </div>
    </main>

    <?php require '../fotter.php'; ?>
</body>
</html>
