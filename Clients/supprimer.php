<?php
require '../auth.php'; // Vérifie si l'utilisateur est connecté

// Connexion à la base de données
$connexion = mysqli_connect("localhost", "root", "", "clicom");
if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8");

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $NCli = mysqli_real_escape_string($connexion, $_GET['id']);

    // Vérifier si le client existe avant de le supprimer
    $verif = "SELECT * FROM client WHERE NCli = ?";
    $stmt_verif = mysqli_prepare($connexion, $verif);
    mysqli_stmt_bind_param($stmt_verif, "s", $NCli);
    mysqli_stmt_execute($stmt_verif);
    $result = mysqli_stmt_get_result($stmt_verif);

    if (mysqli_num_rows($result) > 0) {
        // Suppression du client
        $requete = "DELETE FROM client WHERE NCli = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "s", $NCli);
        $execution = mysqli_stmt_execute($stmt);

        if ($execution) {
            // Redirige vers liste.php avec un message de succès
            header("Location: liste.php?success=supprime");
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
    } else {
        echo "Client introuvable.";
    }
} else {
    echo "ID client non spécifié.";
}

// Fermer la connexion
mysqli_close($connexion);
?>
