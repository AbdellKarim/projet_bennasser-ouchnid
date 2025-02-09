 <?php 
// Connexion à la base de données
$connexion = new mysqli("localhost", "root", "", database: "clicom");
mysqli_set_charset($connexion, "utf8");

session_start();


// Traitement du formulaire
if (isset($_POST['Login'])) {
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $passe = trim($_POST['passe']);




    if (empty($pseudo)) {
        $message_erreur .= "Le champ pseudo est obligatoire<br>\n";
      }
    
      if (empty($passe)) {
        $message_erreur .= "Le mot de passe est obligatoire<br>\n";
      }
    



    // Vérifier les champs
        // Préparer la requête SQL
        $requete = "SELECT * FROM utilisateur WHERE Pseudo = ?";

        $stmt = mysqli_prepare($connexion, $requete);
        mysqli_stmt_bind_param($stmt, "s", $pseudo);
        mysqli_stmt_execute($stmt);
        $resultat = mysqli_stmt_get_result(statement: $stmt);
        $ligne = mysqli_fetch_assoc($resultat);
   
    
        // Vérifier si l'utilisateur existe
        if ( password_verify($passe, $ligne['Password'])) {
            // Démarrer une session et enregistrer les informations
            $_SESSION['pseudo'] = $ligne['Pseudo'];
            $_SESSION['Password'] = $ligne['Password'];
            echo 'gooood';
            // Redirection vers la page contenant le header
            header('Location: index.php');
            exit();
        } else {
            echo "Pseudo ou mot de passe incorrect.";
        }

}




if (isset($_POST['Inscription'])) {

            header('Location: inscription.php');

}



// Fermer la connexion
$connexion->close();
?>






<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <div class="container">
        <h1>LOGIN</h1>
        <form action="" method="POST">
            <table>


                </tr>


                <form action="" method="POST">
                <label for="NCom">pseudo</label>
                <input type="text" id="pseudo" name="pseudo" placeholder="Ex: 12345"  >

  
                <label for="DateCom">Mot de passe </label>
                <input type="password" id="passe" name="passe"  >

                <input type="submit" name="Login" value="Login">
                <input type="submit" name="Inscription" value="Inscription">
            </form>




            </table>
        </form>
        <footer>
        </footer>
    </div>
</body>
</html>






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