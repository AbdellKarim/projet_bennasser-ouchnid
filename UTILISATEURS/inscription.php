<?php
require_once '../SCRIPTS/DB.PHP'; // Connexion à la base de données
require '../header.php';



try {
    $dsn = 'mysql:host=localhost;dbname=clicom;charset=utf8';
    $username = 'root';
    $password = ''; // Votre mot de passe, s'il y en a un
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}






if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et sécuriser les données du formulaire
    $Ncom = htmlspecialchars(trim($_POST['nom']));
   $Ncli = htmlspecialchars(trim($_POST['prenom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $password = trim($_POST['password']);
    $telephone = !empty($_POST['telephone']) ? htmlspecialchars(trim($_POST['telephone'])) : null;
    $aboNewsletter = isset($_POST['aboNewsletter']) ? 1 : 0;
    $commentaire = !empty($_POST['commentaire']) ? htmlspecialchars(trim($_POST['commentaire'])) : null;
    $sexe = htmlspecialchars(trim($_POST['sexe'])); // 'H', 'F', ou 'M'

    // Vérification des champs obligatoires
    if (empty($Ncom) || empty($prenom) || empty($email) || empty($pseudo) || empty($password)) {
        echo "Tous les champs obligatoires doivent être remplis.";
        exit;
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Préparation de la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO utilisateurs (Sexe, Nom, Prenom, Telephone, Email, Pseudo, Password, AboNewsletter, Commentaire) 
                                VALUES (:sexe, :nom, :prenom, :telephone, :email, :pseudo, :password, :aboNewsletter, :commentaire)");
        $stmt->execute([
            ':sexe' => $sexe,
            ':nom' => $Ncom,
            ':prenom' =>$Ncli,
            ':telephone' => $telephone,
            ':email' => $email,
            ':pseudo' => $pseudo,
            ':password' => $hashedPassword,
            ':aboNewsletter' => $aboNewsletter,
            ':commentaire' => $commentaire
        ]);

        echo "Inscription réussie.";
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { // Code d'erreur SQL pour violation de contrainte UNIQUE
            echo "Erreur : L'email ou le pseudo est déjà utilisé.";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
} else {
    echo "Formulaire non soumis.";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Utilisateur</title>
    <link rel="stylesheet" href="../CSS/header.css"> <!-- Lien vers votre fichier CSS si nécessaire -->
</head>
<body>
    <h1>Inscription</h1>
    <form action="inscription.php" method="POST">
        <fieldset>
            <legend>Informations Personnelles</legend>

            <label for="sexe">Sexe :</label>
            <select id="sexe" name="sexe" required>
                <option value="H">Homme</option>
                <option value="F">Femme</option>
                <option value="M">Autre</option>
            </select><br><br>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required><br><br>

            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone"><br><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" required><br><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="aboNewsletter">Abonnement à la newsletter :</label>
            <input type="checkbox" id="aboNewsletter" name="aboNewsletter"><br><br>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="commentaire"></textarea><br><br>

            <button type="submit">S'inscrire</button>
        </fieldset>
    </form>
</body>
</html>
