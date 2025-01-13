
<?php require '../header.php'?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suprimer un document</title>
    <link rel="stylesheet" href="../CSS/header.css">
</head>


<?php
// Inclure la connexion à la base de données
include '../SCRIPTS/DB.php';

// Vérifiez si un IdClient est passé dans l'URL
if (isset($_GET['IdClient'])) {
    $id = intval($_GET['IdClient']); // Sécuriser l'entrée utilisateur

    // Vérifiez que l'IdClient est valide
    if ($id <= 0) {
        die("IdClient invalide !");
    }

    // Supprimer le client de la base de données
    $stmt = $pdo->prepare("DELETE FROM clients WHERE IdClient = :IdClient");
    $stmt->execute(['IdClient' => $id]);

    // Rediriger vers la liste des clients après la suppression
    header("Location: liste.php");
    exit;
} else {
    die("IdClient manquant !");
}
?>


</html>

<?php require '../fotter.php' ?>