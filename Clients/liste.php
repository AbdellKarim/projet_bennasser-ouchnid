




<?php require '../header.php'?>


<?php
require '../SCRIPTS/db.php'; // Connexion à la base

// Récupérer tous les clients
$stmt = $conn->query("SELECT * FROM clients ORDER BY DateInscription DESC");
$clients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/header.css">
    <title>Liste des clients</title>
</head>
<body>
    <h1>Liste des clients</h1>
   <!-- <a href="ajouter.php">Ajouter un client</a>  -->

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['IdClient']) ?></td>
                    <td><?= htmlspecialchars($client['Nom']) ?></td>
                    <td><?= htmlspecialchars($client['Prenom']) ?></td>
                    <td><?= htmlspecialchars($client['Email']) ?></td>
                    <td><?= htmlspecialchars($client['Telephone']) ?></td>
                    <td><?= htmlspecialchars($client['Adresse']) ?></td>
                    <td><?= htmlspecialchars($client['DateInscription']) ?></td>
                    <td>
                        <a href="modifier.php?IdClient=<?= $client['IdClient'] ?>">Modifier</a>
                        <a href="supprimer.php?IdClient=<?= $client['IdClient'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce client ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
  <?php require '../fotter.php' ?>


  
