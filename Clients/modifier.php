<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css"> <!-- Ajoutez vos styles ici -->
    <title>Modifier un Client</title>
</head>
<body>
    

    <h1>Modifier un client</h1>

    <?php require '../header.php';
    require '../SCRIPTS/db.php';


// Vérifiez si un IdClient est passé dans l'URL
if (isset($_GET['IdClient'])) {
    $id = intval($_GET['IdClient']); // Sécuriser l'entrée utilisateur

    // Vérifiez que l'IdClient est valide
    if ($id <= 0) {
        die("IdClient invalide !");
    }

    // Récupérer les données du client correspondant à l'IdClient
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE IdClient = :IdClient");
    $stmt->execute(['IdClient' => $id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le client existe
    if (!$client) {
        die("Client introuvable !");
    }
} else {
    die("IdClient manquant !");
}

// Traiter le formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Ncom = htmlspecialchars($_POST['nom']);
   $Ncli = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone'] ?? null); // Optionnel
    $adresse = htmlspecialchars($_POST['adresse'] ?? null);    // Optionnel

    // Mettre à jour les données du client dans la base de données
    $stmt = $pdo->prepare("UPDATE clients SET 
        Nom = :nom, 
        Prenom = :prenom, 
        Email = :email, 
        Telephone = :telephone, 
        Adresse = :adresse 
        WHERE IdClient = :IdClient");


   $stmt->execute([
    'nom' => $Ncom,
    'prenom' =>$Ncli,
    'email' => $email,
    'telephone' => $telephone,
    'adresse' => $adresse,
    'IdClient' => $id
]);


    echo "Client mis à jour avec succès !";
}
?>



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


    <?php require '../fotter.php'; ?>
</body>
</html>
