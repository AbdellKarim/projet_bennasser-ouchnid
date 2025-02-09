<?php
require '../auth.php'; // Vérifier si l'utilisateur est connecté
require '../header.php'; // Inclusion du header

// Connexion à la base de données
$connexion = mysqli_connect("localhost", "root", "", "clicom");
if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8");

// Initialisation des messages
$message = "";
$message_erreur = "";

// Vérifier si un ID commande est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $NCom = htmlspecialchars($_GET['id']);

    // Récupérer les données actuelles de la commande
    $requete = "SELECT * FROM commande WHERE NCom = ?";
    $stmt = mysqli_prepare($connexion, $requete);
    mysqli_stmt_bind_param($stmt, "i", $NCom);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);

    if ($ligne = mysqli_fetch_assoc($resultat)) {
        $NCli = $ligne['NCli'];
        $DateCom = $ligne['DateCom'];
    } else {
        $message_erreur = "Commande non trouvée !";
    }
} else {
    $message_erreur = "Aucune commande spécifiée.";
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $NCli = trim(htmlspecialchars($_POST['NCli']));
    $DateCom = trim(htmlspecialchars($_POST['DateCom']));

    // Vérifications des champs obligatoires
    if (empty($NCli) || empty($DateCom)) {
        $message_erreur = "Tous les champs sont obligatoires.";
    } else {
        // Mettre à jour les informations dans la base de données
        $requete = "UPDATE commande SET NCli = ?, DateCom = ? WHERE NCom = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "ssi", $NCli, $DateCom, $NCom);
        $execution = mysqli_stmt_execute($stmt);

        if ($execution) {
            $message = "Commande mise à jour avec succès !";
            header("Location: liste.php?success=modifie");
            exit();
        } else {
            $message_erreur = "Erreur lors de la mise à jour.";
        }
    }
}

// Déconnexion de la base de données
mysqli_close($connexion);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Commande</title>
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
        input[type="text"], input[type="date"] {
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
                <label for="NCli">Client (NCli) :</label>
                <input type="text" id="NCli" name="NCli" value="<?php echo isset($NCli) ? $NCli : ''; ?>" required>

                <label for="DateCom">Date de Commande :</label>
                <input type="date" id="DateCom" name="DateCom" value="<?php echo isset($DateCom) ? $DateCom : ''; ?>" required>

                <input type="submit" name="modifier" value="Modifier la Commande">
            </form>
        </div>
    </main>

    <?php require '../fotter.php'; ?>
</body>
</html>
