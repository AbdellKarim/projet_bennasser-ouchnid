<?php require '../header.php';
session_start();

// Vérification si l'utilisateur est connecté et s'il est admin
if (!isset($_SESSION['IdUtilisateur']) || $_SESSION['Role'] !== 'admin') {
    header("Location: admin.php");
    exit;
}

echo "<h1>Bienvenue, " . htmlspecialchars($_SESSION['Nom']) . " (Administrateur)</h1>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="../CSS/header.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <h2>Tableau de Bord Administrateur</h2>
    <ul>
        <li><a href="manage_users.php">Gérer les Utilisateurs</a></li>
        <li><a href="manage_products.php">Gérer les Produits</a></li>
        <li><a href="manage_orders.php">Gérer les Commandes</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>
</body>
</html>