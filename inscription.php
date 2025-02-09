
<?php


// Connexion à la base de données
$connexion = new mysqli("localhost", "root", "", database: "clicom");
mysqli_set_charset($connexion, "utf8");

$Name = "";
$Prenom = "";
$pseudo = "";
$Passe1 = "";
$Passe2 = "";
$Statut = "Supervesion";


$Usr = "CREATE TABLE IF NOT EXISTS utilisateur (
    Name VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
        Pseudo VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Statut VARCHAR(50) NOT NULL
  )";


$stmt = mysqli_prepare($connexion, $Usr);
mysqli_stmt_execute($stmt);




if (isset($_POST['inscrire'])) {
  //***************************

  $Name = trim(htmlspecialchars($_POST['nom'], ENT_COMPAT));
  $Prenom = trim(htmlspecialchars($_POST['prenom'], ENT_COMPAT));
  $pseudo = htmlspecialchars($_POST['pseudo']);
  $Passe1 = trim($_POST['passe1']);
  $Passe2 = trim($_POST['passe2']);

echo $Name;
echo  $Passe2;
echo  $Prenom;
echo $pseudo ;
echo $Passe1;





  if (strcmp($Passe1, $Passe2) != 0) {
    $message_erreur .= "Les mots de passe sont différents<br>\n";
  }



  // Cryptage du mot de passe
  $passe_chiffre = password_hash($Passe1, PASSWORD_DEFAULT);

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    $Verifcation = "SELECT COUNT(*) as total FROM utilisateur"; //verification de la table
    $resultat = mysqli_query($connexion, $Verifcation);
    $row = mysqli_fetch_assoc($resultat);
if ($resultat) {
    
    $nombre_utilisateurs = $row['total'];
    if ($nombre_utilisateurs == 0) {
        $insertion = "INSERT INTO utilisateur (Name, Prenom, Pseudo, Password, Statut) VALUES (?, ?, ?, ?, ?)";
       $stmt1 = mysqli_prepare($connexion, $insertion);
       mysqli_stmt_bind_param($stmt1,"sssss", $Name, $Prenom, $pseudo, $passe_chiffre, $Statut);
       mysqli_stmt_execute($stmt1);



       header('Location: login.php');
    }

    }
    

    // 


 
}
}

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>

    <main>
        <!-- **************************************** -->
        <!-- Messages éventuels de l'application      -->
        <!-- **************************************** -->

        <!-- Affichage du formulaire -->
        <section>
            <h2>Inscription</h2>
            <form action="" method="POST">
                
                <fieldset>
                    <legend>Coordonnées</legend>

                    <p>
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" placeholder="Nom" maxlength="100" required>
                    </p>

                    <p>
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" placeholder="Prénom" maxlength="100" required>
                    </p>
                </fieldset>

                <fieldset>
                    <legend>Informations de connexion</legend>

                    <p>
                        <label for="pseudo">Pseudo :</label>
                        <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" minlength="2" maxlength="10" required>
                    </p>  

                    <p>
                        <label for="passe1">Mot de passe :</label>
                        <input type="password" id="passe1" name="passe1" placeholder="Mot de passe" minlength="6" required>
                    </p>  

                    <p>
                        <label for="passe2">Confirmer le mot de passe :</label>
                        <input type="password" id="passe2" name="passe2" placeholder="Mot de passe" minlength="6" required>
                    </p>
                </fieldset>

                <p>
                    <input type="submit" name="inscrire" value="S'inscrire">
                </p>

            </form>
        </section>
    </main>

</body>
</html>