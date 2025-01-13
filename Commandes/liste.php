<?php require '../header.php';
    require '../SCRIPTS/db.php';
session_start();// Démarrer ou reprendre la session




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
    <link rel="stylesheet" href=""> <!-- Lien vers votre fichier CSS -->
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
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="../UTILISATEURS/logout.php">Se déconnecter</a></p>
</body>
</html>

