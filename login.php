<?php
// Connexion à la base de données
$connexion = new mysqli("localhost", "root", "", "clicom");
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}
mysqli_set_charset($connexion, "utf8");

// Traitement du formulaire
if (isset($_POST['connecter'])) {
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $passe = trim($_POST['passe']);

    // Vérifier les champs
    if (!empty($pseudo) && !empty($passe)) {
        // Préparer la requête SQL
        $requete = "SELECT * FROM utilisateur WHERE Pseudo = ? AND Password = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "ss", $pseudo, $passe);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        // Vérifier si l'utilisateur existe
        if ($ligne = mysqli_fetch_assoc($resultat)) {
            // Démarrer une session et enregistrer les informations
            session_start();
            $_SESSION['idutilisateur'] = $ligne['IdUtilisateur'];
            $_SESSION['pseudo'] = $ligne['Pseudo'];
            $_SESSION['nom'] = $ligne['Nom'];
            $_SESSION['prenom'] = $ligne['Prenom'];

            // Redirection vers la page contenant le header
            header('Location: accueil.php');
            exit();
        } else {
            echo "Pseudo ou mot de passe incorrect.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}

// Fermer la connexion
$connexion->close();
?>
