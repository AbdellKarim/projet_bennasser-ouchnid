
<?php require '../header.php'?>





<?php
session_start();
require_once '../SCRIPTS/db.php';

$host = 'localhost'; // Hôte de la base de données (généralement 'localhost')
$dbname = 'clicom'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur de la base de données
$password = ''; // Mot de passe de la base de données (laisser vide si pas de mot de passe)

try {
    // DSN (Data Source Name) pour la connexion
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    // Options PDO pour configurer le comportement de la connexion
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lancer des exceptions en cas d'erreur
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut : tableau associatif
        PDO::ATTR_EMULATE_PREPARES => false // Désactiver l'émulation des requêtes préparées
    ];

    // Création de l'objet PDO
    $conn = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    // Gestion de l'erreur de connexion
    die("Erreur de connexion : " . $e->getMessage());
}

if (!isset($_SESSION['IdUtilisateur'])) {
    header("Location: login.html");
    exit;
}

try {
    $stmt = $conn->prepare("SELECT commandes.idCommande, commandes.dateCommande, commandes.totalCommande, commandes.statut, utilisateurs.Nom, utilisateurs.Prenom
                            FROM commandes
                            JOIN utilisateurs ON commandes.idClient = utilisateurs.IdUtilisateur
                            ORDER BY commandes.dateCommande DESC");
    $stmt->execute();
    $commandes = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des commandes : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Commandes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Liste des Commandes</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID Commande</th>
                <th>Date</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['idCommande']) ?></td>
                    <td><?= htmlspecialchars($commande['dateCommande']) ?></td>
                    <td><?= htmlspecialchars($commande['totalCommande']) ?></td>
                    <td><?= htmlspecialchars($commande['statut']) ?></td>
                    <td><?= htmlspecialchars($commande['Nom']) . ' ' . htmlspecialchars($commande['Prenom']) ?></td>
                    <td>
                        <a href="modifier_commande.php?idCommande=<?= htmlspecialchars($commande['idCommande']) ?>">Modifier</a> |
                        <a href="supprimer_commande.php?idCommande=<?= htmlspecialchars($commande['idCommande']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette commande ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="logout.php">Se déconnecter</a></p>
</body>
</html>



<?php require '../fotter.php' ?>