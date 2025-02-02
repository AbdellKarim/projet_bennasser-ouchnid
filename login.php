<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #007bff, #6c757d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
        }

        td {
            padding: 15px;
            text-align: left;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        footer {
            margin-top: 20px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome</h1>
        <form action="login.php" method="POST">
            <table>
                <tr>
                    <td><label for="pseudo">Pseudo</label></td>
                    <td><input type="text" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo" required></td>
                </tr>
                <tr>
                    <td><label for="passe">Mot de passe</label></td>
                    <td><input type="password" id="passe" name="passe" placeholder="Entrez votre mot de passe" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="connecter" value="Se connecter">
                    </td>
                </tr>
            </table>
        </form>
        <footer>
            &copy; 2025 - Clicom Project. Tous droits réservés.
        </footer>
    </div>
</body>
</html>


<?php
// Connexion à la base de données
$connexion = new mysqli("localhost", "root", "", "clicom");
if ($connexion->connect_error) {
    die("Erreur de connexion : " . $connexion->connect_error);
}
mysqli_set_charset($connexion, "utf8");

// Traitement du formulaire
if (isset($_POST['connecter'])) {
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $passe = trim($_POST['passe']);

    // Vérifier les champs
    if (!empty($pseudo) && !empty($passe)) {
        // Préparer la requête SQL
        $requete = "SELECT * FROM utilisateur WHERE Pseudo = ? AND Password = ?";
        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "ss", $pseudo, $passe);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result($stmt);

        // Vérifier si l'utilisateur existe
        if ($ligne = mysqli_fetch_assoc($resultat)) {
            // Démarrer une session et enregistrer les informations
            session_start();
            $_SESSION['idutilisateur'] = $ligne['IdUtilisateur'];
            $_SESSION['pseudo'] = $ligne['Pseudo'];
            $_SESSION['nom'] = $ligne['Nom'];
            $_SESSION['prenom'] = $ligne['Prenom'];

            // Redirection vers la page contenant le header
            header('Location: index.php');
            exit();
        } else {
            echo "Pseudo ou mot de passe incorrect.";
        }
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}

// Fermer la connexion
$connexion->close();
?>