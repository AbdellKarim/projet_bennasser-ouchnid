<?php
require '../auth.php'; // Vérifie si l'utilisateur est connecté

// Connexion à la base de données
$connexion = mysqli_connect("localhost", "root", "", "clicom");
if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
mysqli_set_charset($connexion, "utf8");

// Vérifier si un ID produit est passé dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $NPro = mysqli_real_escape_string($connexion, $_GET['id']);

    // ✅ Supprimer d'abord les détails associés dans la table "detail"
    $delete_detail = "DELETE FROM detail WHERE NPro = ?";
    $stmt_detail = mysqli_prepare($connexion, $delete_detail);
    mysqli_stmt_bind_param($stmt_detail, "s", $NPro);
    mysqli_stmt_execute($stmt_detail);
    mysqli_stmt_close($stmt_detail); // Fermeture de la requête pour éviter les conflits

    // ✅ Supprimer maintenant le produit dans la table "produit"
    $delete_produit = "DELETE FROM produit WHERE NPro = ?";
    $stmt_produit = mysqli_prepare($connexion, $delete_produit);
    mysqli_stmt_bind_param($stmt_produit, "s", $NPro);
    $execution = mysqli_stmt_execute($stmt_produit);
    mysqli_stmt_close($stmt_produit); // Fermeture de la requête après exécution

    if ($execution) {
        // Redirige vers liste.php avec un message de succès
        header("Location: liste.php?success=supprime");
        exit();
    } else {
        echo "Erreur lors de la suppression du produit.";
    }
} else {
    echo "ID produit non spécifié.";
}

// Fermer la connexion
mysqli_close($connexion);
?>
