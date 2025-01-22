<?php
// La variable $message contiendra les éventuels messages de l'application à afficher
$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur de l'application à afficher
$message_erreur = "";

// **********************************************
// Traitement du formulaire
//
// Initialisation des variables contenant les données saisies dans le formulaire
// et utilisées pour remplir le formulaire
$Ncom = "";
$Ncli = "";
$DateCom = "";

// Gestion d'erreur manuelle : désactivation des rapports d'erreur
error_reporting(0); // Désactivation du rapport d'erreurs de PHP
mysqli_report(MYSQLI_REPORT_OFF); // Désactivation du rapport d'erreur mysqli
// Connexion à la base de données bibliotheque du serveur localhost
$connexion = mysqli_connect("localhost", "root", "", "bibliotheque");
if ($connexion) {
  // Changement du jeu de caractères pour utf-8 
  mysqli_set_charset($connexion, "utf8");
} else {
  $message_erreur .= "Erreur de connexion<br>\n";
  $message_erreur .= "  Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
}

if (isset($_POST['inscrire'])) {
  //***************************
  // Clic sur le bouton "S'inscrire" de valeur name="inscrire"
  // Traitement du formulaire
  // 
  // Filtrage du contenu de $_POST et assignation à des variables locales
  // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
  // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
  $Ncom = trim(htmlspecialchars($_POST['Ncom'], ENT_COMPAT));
  $Ncli = trim(htmlspecialchars($_POST['Ncli'], ENT_COMPAT));
  $DateCom = htmlspecialchars($_POST['DateCom']);

  // Vérification de toutes les valeurs saisies
  if (empty($Ncom)) {
    $message_erreur .= "Le champ NCom est obligatoire<br>\n";
  }  

  if (empty($Ncli)) {
    $message_erreur .= "Le champ Ncli est obligatoire<br>\n";
  } elseif (!preg_match('/^([a-zA-Z]){1}[0-9]{3}$/u', $Ncli)) {
    $message_erreur .= "Le Ncli doit comporter une lettre et 3 chiffres<br>\n";
  }

  if (empty($DateCom)) {
    $message_erreur .= "La date est obligatoire<br>\n";
  } elseif (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $DateCom)) {
    $message_erreur .= "Le format de la date doit être DD-MM-YYYY<br>\n";
  } else {
    $date = DateTime::createFromFormat('d-m-Y', $DateCom);
    if (!$date || $date->format('d-m-Y') !== $DateCom) {
        $message_erreur .= "La date n'est pas valide<br>\n";
    }
  }

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    // Requête d'insertion de l'utilisateur dans la table produit

$requete = "INSERT INTO commande (Ncom, Ncli, DateCom) VALUES ('$Ncom', '$Ncli', '$DateCom')";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Affiche un message de confirmation ainsi que les valeurs saisies
      $message .= "<p>Nous avons pris en compte votre inscription.\n";
      $message .= "<br>Voici les données saisies :</p>\n";
      $message .= "<ul>\n";
      $message .= "<li>Ncom : " . $Ncom . "</li>\n";
      $message .= "<li>Ncli : " . $Ncli . "</li>\n";
      $message .= "<li>DateCom : " . $DateCom . "</li>\n";
      $message .= "</ul>\n";
    } else {
      $message_erreur .= "Erreur lors de l'insertion des données<br>\n";
    }
  }
}

// Déconnexion de la base de données
if ($connexion) {
  mysqli_close($connexion);
}
?> 
<!doctype html>
<!-- **************************************** -->
<!-- Construction de la page HTML             --> 
<html>
  <head>
    <meta charset="UTF-8">
    <title>Inscription</title>
  </head>
  <body>

    <header>
      <h1><a href="index.php">AJOUTER UNE COMMANDE</a></h1>
    </header>
    <nav>
      <!-- Insérer une barre de navigation ici -->
    </nav>
    <main>
      <?php
      if (!empty($message_erreur) || !empty($message)) {
        ?>
        <!-- **************************************** -->
        <!-- Messages éventuels de l'application      -->
        <section>
          <h2>Logs</h2>
          <?php
          if (!empty($message_erreur)) {
            echo "<section>\n" . $message_erreur . "</section>\n";
          }
          if (!empty($message)) {
            echo "<section>\n" . $message . "</section>\n";
          }
          ?>
        </section>          
        <?php
      }
      // S'il y a eu des erreurs ou si aucun appui sur le bouton "S'inscrire" 
      if (!empty($message_erreur) || !isset($_POST['inscrire'])) {
        ?>
        <!-- **************************************** -->
        <!-- Affichage du formulaire                  -->
        <section>     
          <h2>Inscription</h2>
          <form action="" method="POST">
            <section>
              <h3>Coordonnées</h3>
   
              <p>
                <label for="Ncom">NCom </label>
                <input type="text" id="Ncom" name="Ncom" placeholder="Ncom"  value="<?php echo $Ncom ?>" maxlength="5" required>
              </p>
              <p>
                <label for="Ncli">Ncli </label>
                <input type="text" id="Ncli" name="Ncli" placeholder="Ncli"  value="<?php echo $Ncli ?>" maxlength="100" required>
              </p>
              <p>
                <label for="DateCom">DateCom </label>
                <input type="text" id="DateCom" name="DateCom" placeholder="DateCom"  value="<?php echo $DateCom ?>" maxlength="250" required>
              </p>

            </section>

            <section>
              <p><input type="submit" name="inscrire" value="S'inscrire"></p>
            </section>
          </form>
        </section>
        <?php
      }
      ?> 
    </main>
    <footer>
      <!-- Insérer un pied de page ici -->
    </footer>
  </body>
</html>
