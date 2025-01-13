

<?php require '../header.php';
    require_once '../SCRIPTS/db.php';
    session_start();// Démarrer ou reprendre la session


// Gestion de l'ajout d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_order'])) {
    $idClient = htmlspecialchars($_POST['idClient']);
    $idUtilisateur = 1; // Remplacer par l'ID de l'utilisateur authentifié
    $dateCommande = date('Y-m-d H:i:s');
    $totalCommande = 0;
    $produits = $_POST['produits'];

    foreach ($produits as $produit) {
        $totalCommande += $produit['quantite'] * $produit['prix'];
    }

    // Requête préparée pour l'insertion dans la table commandes
    $REQUETE = "INSERT INTO commandes (idClient, idUtilisateur, dateCommande, totalCommande, statut) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($REQUETE);

    if (    $stmt->execute([':idClient' => $idClient,':idUtilisateur' => $idUtilisateur,':dateCommande' => $dateCommande,':totalCommande' => $totalCommande,':statut' => $produits])) {
        $message = "Client ajouté avec succès.";
    } else {
        $message_erreur = "Erreur lors de l'ajout du client.";
    }


    if ($stmt->execute()) {
        echo "Commande ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de la commande : ";
    }

}












// Gestion de l'ajout d'un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $nomProduit = $_POST['nomProduit'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];

    $stmt = $conn->prepare("INSERT INTO produits (nomProduit, description, prix, stock) VALUES (?, ?, ?, ?)");
    $stmt->bindParam("ssdi", $nomProduit, $description, $prix, $stock);

    if ($stmt->execute()) {
        echo "<p>Produit ajouté avec succès !</p>";
    } else {
        echo "<p>Erreur : ". "</p>";
    }


}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout Commande et Produit</title>
</head>
<body>
    <h1>Ajout d'une Commande</h1>
    <form method="POST">
        <label for="idClient">ID Client :</label>
        <input type="number" name="idClient" required><br>

        <h3>Produits :</h3>
        <div id="produits">
            <div class="produit">
                <label>ID Produit :</label>
                <input type="number" name="produits[0][idProduit]" required>
                <label>Quantité :</label>
                <input type="number" name="produits[0][quantite]" required>
                <label>Prix :</label>
                <input type="number" step="0.01" name="produits[0][prix]" required>
            </div>
        </div>

        <button type="submit" name="add_order">Ajouter la commande</button>
    </form>

 
</body>
</html>
