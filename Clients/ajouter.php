<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css"> <!-- Ajoutez vos styles ici -->
    <title>Ajouter un Client</title>
</head>
<body>
    <?php require '../header.php'; require '../SCRIPTS/DB.php'; // Connexion à la base de données ?>




    <!--	IdClient	Nom	Prenom	Email	Telephone	Adresse	DateInscription	IdUtilisateur-->
    <!-- Formulaire -->
    <form method="POST">
        <!-- Informations Client -->
        <fieldset>
            <legend>Informations du Client</legend>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required><br><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone"><br><br>

            <label for="adresse">Adresse :</label>
            <textarea id="adresse" name="adresse"></textarea><br><br>
        </fieldset>

 

        <button type="submit">Ajouter</button>
    </form>





    <?php


$nom = '';
$prenom = '';
$email = '';
$telephone = '';
$adresse = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Récupération des données du formulaire
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$email = htmlspecialchars($_POST['email']);
$telephone = htmlspecialchars($_POST['telephone'] ?? null); // Optionnel
$adresse = htmlspecialchars($_POST['adresse'] ?? null);    // Optionnel
}

// Insertion des informations du client
$sqlClient = "INSERT INTO Clients (Nom, Prenom, Email, Telephone, Adresse) 
              VALUES (:nom, :prenom, :email, :telephone, :adresse)";

    $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE email = ?");
    $stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    $message_erreur = "L'adresse email est déjà utilisée.";
} else {      
    $stmt = $conn->prepare($sqlClient);
if (    $stmt->execute([':nom' => $nom,':prenom' => $prenom,':email' => $email,':telephone' => $telephone,':adresse' => $adresse])) {
    $message = "Client ajouté avec succès.";
} else {
    $message_erreur = "Erreur lors de l'ajout du client.";
}
}
?>



<!--

// $idClient = $pdo->lastInsertId();

// Ajouter des commandes pour ce client
$sqlCommande = "INSERT INTO Commandes (IdClient, TotalCommande) VALUES (:idClient, :total)";
$stmt = $pdo->prepare($sqlCommande);
$stmt->execute([':idClient' => $idClient, ':total' => 100.50]);
$stmt->execute([':idClient' => $idClient, ':total' => 200.00]);

-->











    <?php require '../fotter.php'; ?>
</body>
</html>

