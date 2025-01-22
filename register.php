<?php
require 'SCRIPTS/DB.php'; // Connexion à la base

$message = "";
$message_erreur = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $passe = trim($_POST['passe']);
    $Ncom = htmlspecialchars($_POST['nom']);
   $Ncli = htmlspecialchars($_POST['prenom']);
   DateCom = htmlspecialchars($_POST['mail']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $sexe = htmlspecialchars($_POST['sexe']);
    $aboNewsletter = isset($_POST['aboNewsletter']) ? 1 : 0;
    $commentaire = htmlspecialchars($_POST['commentaire']);

    // Validation des champs obligatoires
    if (empty($pseudo) || empty($passe) || empty($Ncom) || empty($prenom) || empty($mail)) {
        $message_erreur = "Tous les champs obligatoires (*) doivent être remplis.";
    } else {
        // Vérification si le pseudo ou l'email existe déjà
        $requete = $pdo->prepare("SELECT * FROM utilisateur WHERE Pseudo = ? OR Mail = ?");
        $requete->execute([$pseudo,DateCom]);
        if ($requete->fetch()) {
            $message_erreur = "Le pseudo ou l'email est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $passe_hache = password_hash($passe, PASSWORD_DEFAULT);

            // Insertion dans la base
            $requete = $pdo->prepare(
                "INSERT INTO utilisateur (Sexe, Nom, Prenom, Mail, Telephone, Pseudo, Password, AboNewsletter, Commentaire) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            if ($requete->execute([$sexe, $Ncom,$Ncli,DateCom, $telephone, $pseudo, $passe_hache, $aboNewsletter, $commentaire])) {
                $message = "Utilisateur enregistré avec succès.";
            } else {
                $message_erreur = "Erreur lors de l'enregistrement.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <?php if (!empty($message_erreur)): ?>
        <div style="color: red;"><?php echo $message_erreur; ?></div>
    <?php endif; ?>
    <?php if (!empty($message)): ?>
        <div style="color: green;"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="post">
        <label for="sexe">Sexe :</label>
        <select id="sexe" name="sexe">
            <option value="M">Homme</option>
            <option value="F">Femme</option>
        </select>

        <label for="nom">Nom* :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom* :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="mail">Email* :</label>
        <input type="email" id="mail" name="mail" required>

        <label for="telephone">Téléphone :</label>
        <input type="tel" id="telephone" name="telephone">

        <label for="pseudo">Pseudo* :</label>
        <input type="text" id="pseudo" name="pseudo" maxlength="10" required>

        <label for="passe">Mot de passe* :</label>
        <input type="password" id="passe" name="passe" required>

        <label for="aboNewsletter">Abonnement à la newsletter :</label>
        <input type="checkbox" id="aboNewsletter" name="aboNewsletter">

        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire"></textarea>

        <button type="submit" name="register">S'inscrire</button>
    </form>
</body>
</html>
