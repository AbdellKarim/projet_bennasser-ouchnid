<?php require '../header.php';




  
    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'clicom';
    $username = 'root';
    $password = '';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }




// Vérification si l'utilisateur est connecté et s'il est client
if (!isset($_SESSION['IdUtilisateur']) || $_SESSION['Role'] !== 'client') {
    header("Location: clients.php");
    exit;
}

echo "<h1>Bienvenue, " . htmlspecialchars($_SESSION['Nom']) . " (Client)</h1>";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Client</title>
    <link rel="stylesheet" href="../CSS/header.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <h2>Tableau de Bord Client</h2>
    <ul>
        <li><a href="place_order.php">Passer une Commande</a></li>
        <li><a href="view_orders.php">Voir Mes Commandes</a></li>
        <li><a href="edit_profile.php">Modifier Mon Profil</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>
</body>
</html>
