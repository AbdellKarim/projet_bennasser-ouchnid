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
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8");

// **********************************************
// Vérification de l'ID client dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $NCli = mysqli_real_escape_string($connexion, $_GET['id']);

    // Récupération des informations du client
    $requete = "SELECT * FROM client WHERE NCli = ?";
    $stmt = mysqli_prepare($connexion, $requete);
    mysqli_stmt_bind_param($stmt, "s", $NCli);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);

    if ($ligne = mysqli_fetch_assoc($resultat)) {
        $Nom = $ligne['Nom'];
        $Prenom = $ligne['Prenom'];
        $Adresse = $ligne['Adresse'];
        $CP = $ligne['CP'];
        $Ville = $ligne['Ville'];
        $CAT = $ligne['CAT'];
        $Compte = $ligne['Compte'];
    } else {
        $message_erreur .= "Client non trouvé !<br>";
    }
} else {
    $message_erreur .= "Aucun client spécifié.<br>";
}

// **********************************************
// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nom = mysqli_real_escape_string($connexion, trim($_POST['Nom']));
    $Prenom = mysqli_real_escape_string($connexion, trim($_POST['Prenom']));
    $Adresse = mysqli_real_escape_string($connexion, trim($_POST['Adresse']));
    $CP = mysqli_real_escape_string($connexion, trim($_POST['CP']));
    $Ville = mysqli_real_escape_string($connexion, trim($_POST['Ville']));
    $CAT = mysqli_real_escape_string($connexion, trim($_POST['CAT']));
    $Compte = mysqli_real_escape_string($connexion, trim($_POST['Compte']));

    // Vérifications des champs
    if (empty($Nom) || empty($Adresse) || empty($CP) || empty($Ville) || empty($Compte)) {
        $message_erreur .= "Tous les champs obligatoires doivent être remplis.<br>\n";
    } elseif (!preg_match('/^[0-9]{5}$/', $CP)) {
        $message_erreur .= "Le Code Postal doit contenir 5 chiffres.<br>\n";
    } elseif (!is_numeric($Compte)) {
        $message_erreur .= "Le solde du compte doit être un nombre valide.<br>\n";
    }

    // Si aucune erreur, mise à jour des informations
    if (empty($message_erreur)) {
        $requete = "UPDATE client SET Nom = ?, Prenom = ?, Adresse = ?, CP = ?, Ville = ?, CAT = ?, Compte = ? WHERE NCli = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "ssssssds", $Nom, $Prenom, $Adresse, $CP, $Ville, $CAT, $Compte, $NCli);
        $execution = mysqli_stmt_execute($stmt);

        if ($execution) {
            $message .= "Client modifié avec succès !<br>\n";
            header("Location: liste.php?success=modifie");
            exit();
        } else {
            $message_erreur .= "Erreur lors de la modification du client.<br>\n";
        }
    }
}

// **********************************************
// Déconnexion de la base de données
mysqli_close($connexion);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Client</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
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
            background: #ffc107;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background: #e0a800;
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
        <h1>Modifier un Client</h1>
    </header>

    <main>
        <div class="form-container">
            <h2>Formulaire de Modification</h2>

            <?php if (!empty($message_erreur)) { ?>
                <p class="message-erreur"><?php echo $message_erreur; ?></p>
            <?php } ?>

            <?php if (!empty($message)) { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>

            <form action="" method="POST">
                <label for="Nom">Nom :</label>
                <input type="text" id="Nom" name="Nom" value="<?php echo $Nom; ?>" required>

                <label for="Prenom">Prénom :</label>
                <input type="text" id="Prenom" name="Prenom" value="<?php echo $Prenom; ?>">

                <label for="Adresse">Adresse :</label>
                <input type="text" id="Adresse" name="Adresse" value="<?php echo $Adresse; ?>" required>

                <label for="CP">Code Postal :</label>
                <input type="text" id="CP" name="CP" value="<?php echo $CP; ?>" required>

                <label for="Ville">Ville :</label>
                <input type="text" id="Ville" name="Ville" value="<?php echo $Ville; ?>" required>

                <label for="CAT">Catégorie :</label>
                <input type="text" id="CAT" name="CAT" value="<?php echo $CAT; ?>">

                <label for="Compte">Solde du Compte (€) :</label>
                <input type="number" step="0.01" id="Compte" name="Compte" value="<?php echo $Compte; ?>" required>

                <input type="submit" value="Modifier le Client">
            </form>
        </div>
    </main>

    <?php require '../fotter.php'; ?>
</body>
</html>
