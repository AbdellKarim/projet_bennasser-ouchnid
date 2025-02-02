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
// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NCli = mysqli_real_escape_string($connexion, trim($_POST['NCli']));
    $Nom = mysqli_real_escape_string($connexion, trim($_POST['Nom']));
    $Prenom = mysqli_real_escape_string($connexion, trim($_POST['Prenom']));
    $Adresse = mysqli_real_escape_string($connexion, trim($_POST['Adresse']));
    $CP = mysqli_real_escape_string($connexion, trim($_POST['CP']));
    $Ville = mysqli_real_escape_string($connexion, trim($_POST['Ville']));
    $CAT = mysqli_real_escape_string($connexion, trim($_POST['CAT']));
    $Compte = mysqli_real_escape_string($connexion, trim($_POST['Compte']));

    // Vérifications des champs
    if (empty($NCli) || empty($Nom) || empty($Adresse) || empty($CP) || empty($Ville) || empty($Compte)) {
        $message_erreur .= "Tous les champs obligatoires doivent être remplis.<br>\n";
    } elseif (!preg_match('/^[A-Z][0-9]{3}$/', $NCli)) {
        $message_erreur .= "L'ID Client doit être une lettre suivie de 3 chiffres (ex: A123).<br>\n";
    } elseif (!preg_match('/^[0-9]{5}$/', $CP)) {
        $message_erreur .= "Le Code Postal doit contenir 5 chiffres.<br>\n";
    } elseif (!is_numeric($Compte)) {
        $message_erreur .= "Le solde du compte doit être un nombre valide.<br>\n";
    }

    // Si aucune erreur, insertion dans la base de données
    if (empty($message_erreur)) {
        $requete = "INSERT INTO client (NCli, Nom, Prenom, Adresse, CP, Ville, CAT, Compte) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "sssssssd", $NCli, $Nom, $Prenom, $Adresse, $CP, $Ville, $CAT, $Compte);
        $execution = mysqli_stmt_execute($stmt);

        if ($execution) {
            $message .= "Client ajouté avec succès !<br>\n";
            header("Location: liste.php?success=ajout");
            exit();
        } else {
            $message_erreur .= "Erreur lors de l'ajout du client.<br>\n";
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
    <title>Ajouter un Client</title>
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
        <h1>Ajouter un Client</h1>
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
                <label for="NCli">ID Client :</label>
                <input type="text" id="NCli" name="NCli" placeholder="Ex: A123" required>

                <label for="Nom">Nom :</label>
                <input type="text" id="Nom" name="Nom" required>

                <label for="Prenom">Prénom :</label>
                <input type="text" id="Prenom" name="Prenom">

                <label for="Adresse">Adresse :</label>
                <input type="text" id="Adresse" name="Adresse" required>

                <label for="CP">Code Postal :</label>
                <input type="text" id="CP" name="CP" required>

                <label for="Ville">Ville :</label>
                <input type="text" id="Ville" name="Ville" required>

                <label for="CAT">Catégorie :</label>
                <input type="text" id="CAT" name="CAT">

                <label for="Compte">Solde du Compte (€) :</label>
                <input type="number" step="0.01" id="Compte" name="Compte" required>

                <input type="submit" value="Ajouter le Client">
            </form>
        </div>
    </main>

    <?php require '../fotter.php'; ?>
</body>
</html>
