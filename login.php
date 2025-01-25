<?php
// Connexion à la base de données
$connexion = new mysqli("localhost", "root", "", "clicom");
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}
mysqli_set_charset($connexion, "utf8");

// Initialisation des messages
$message_erreur = "";

// Traitement du formulaire
if (isset($_POST['connecter'])) {
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $passe = trim($_POST['passe']);

    // Vérifier que les champs ne sont pas vides
    if (!empty($pseudo) && !empty($passe)) {
        // Préparer la requête SQL
        $requete = "SELECT * FROM utilisateur WHERE Pseudo = ? AND Password = ?";
        $stmt = mysqli_prepare($connexion, $requete);

        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . mysqli_error($connexion));
        }

        mysqli_stmt_bind_param($stmt, "ss", $pseudo, $passe);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        // Vérifier si un utilisateur a été trouvé
        if ($ligne = mysqli_fetch_assoc($resultat)) {
            session_start();
            $_SESSION['idutilisateur'] = $ligne['IdUtilisateur'];
            $_SESSION['pseudo'] = $ligne['Pseudo'];
            $_SESSION['nom'] = $ligne['Nom'];
            $_SESSION['prenom'] = $ligne['Prenom'];

            // Rediriger vers la page d'accueil
            header('Location: index.php');
            exit();
        } else {
            $message_erreur = "Pseudo ou mot de passe incorrect.";
        }
    } else {
        $message_erreur = "Tous les champs sont obligatoires.";
    }
}

// Afficher un message d'erreur si nécessaire
if (!empty($message_erreur)) {
    echo "<p style='color: red;'>$message_erreur</p>";
}

// Fermer la connexion
$connexion->close();
?>
