


<?php
session_start();



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





require_once '../SCRIPTS/db.php'; // Connexion à la base de données
require '../header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Vérification des informations de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE Email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['IdUtilisateur'] = $user['IdUtilisateur'];
        $_SESSION['Nom'] = $user['Nom'];
        $_SESSION['Role'] = $user['Role'];

        // Redirection selon le rôle
        if ($user['Role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: clients.php");
        }
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../CSS/header.css"> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <h1>Connexion</h1>
    <form action="login.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>

<?php require '../fotter.php' ?>