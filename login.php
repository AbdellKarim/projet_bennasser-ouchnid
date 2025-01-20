

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page</title>
    <link rel="stylesheet" href="../CSS/header.css">
</head>
<body>
</body>
</html>

  <?php require "header.php"; 
  /*
// Connexion à la base de données
$connexion = mysqli_connect("localhost", "root", "", "clicom");
if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8");

// Initialisation des messages
$message_erreur = "";

// Traitement du formulaire
if (isset($_POST['connecter'])) {
    // Récupérer les champs du formulaire
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $passe = trim($_POST['passe']);

    // Validation des champs
    if (empty($pseudo) || empty($passe)) {
        $message_erreur = "Tous les champs sont obligatoires.";
    } else {
        // Préparer la requête pour vérifier le pseudo
        $requete = "SELECT * FROM utilisateur WHERE Pseudo = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "s", $pseudo);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        if ($ligne = mysqli_fetch_assoc($resultat)) {
            // Vérifier le mot de passe
            if (password_verify($passe, $ligne['Password'])) {
                // Démarrer la session si le mot de passe est correct
                session_start();
                $_SESSION['idutilisateur'] = $ligne['IdUtilisateur'];
                $_SESSION['pseudo'] = $ligne['Pseudo'];
                $_SESSION['nom'] = $ligne['Nom'];
                $_SESSION['prenom'] = $ligne['Prenom'];

                // Redirection vers la page d'accueil
                header('Location: index.php');
                exit();
            } else {
                $message_erreur = "Mot de passe incorrect.";
            }
        } else {
            $message_erreur = "Pseudo non trouvé.";
        }
    }
}

// Afficher un message d'erreur si nécessaire
if (!empty($message_erreur)) {
    echo "<p style='color: red;'>$message_erreur</p>";
}

// Fermer la connexion
mysqli_close($connexion);
*/

?>

