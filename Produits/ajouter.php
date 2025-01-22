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
  // 


  if (empty($Ncom)) {
    $message_erreur .= "Le champ prenom est obligatoire<br>\n";
  } elseif (strlen($Ncom) > 5) {
    $message_erreur .= "Le prénom ne doit pas comporter plus de 5 chifres <br>\n";
  } elseif (!preg_match('/^[0-9]*$/u',$Ncli)) {
    $message_erreur .= "Le prénom ne doit comporter que des chifres<br>\n";
  }



  if (empty($Ncli)) {
    $message_erreur .= "Le champ pseudo est obligatoire<br>\n";
  } elseif (!preg_match('/^[0-9]{3}([a-zA-Z])/u', $Ncli)) {
    $message_erreur .= "Le Ncli ne doit comporter une lettre et 3 chiffres<br>\n";
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
    //*******************************************
    // Saisie des données du formulaire dans la table utilisateur
    // après verification que le pseudo et le mail n'existent 
    // pas déjà dans la table
    // 
    // Vérification que le pseudo n'existe pas dans la table utilisateur
    $requete = "select * from produit where NCom = '$NCom'";
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat
      if (mysqli_num_rows($resultat) != 0) {
        // Le pseudo existe déjà dans la table
        $message_erreur .= "Le pseudo $NCom existe déjà<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête $requete<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }


  }

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    // Requête d'insertion de l'utilisateur dans la table utilisateur
    $requete = "insert into produit (Ncom,Nom,DateCom)"
            . "values ('$Ncom',\"$Ncli\",";
    $requete .= empty($DateCom) ? "null" : "'$date('d-m-Y');'";
    $requete .= ");";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Affiche un message de confirmation ainsi que les valeurs saisies
      $message .= "<p>Nous avons pris en compte votre inscription.\n";
      $message .= "<br>Voici les données saisies :</p>\n";
      $message .= "<ul>\n";
      $message .= "<li>Ncom : " . $Ncom . "</li>\n";
      $message .= "<li>Ncli : " . $Ncli . "</li>\n";
      $message .= "<li>DateCom : " .$DateCom . "</li>\n";
      if (empty($DateCom)) {
        $message .= "<li>Téléphone : Non saisi </li>\n";
      } else {
        $message .= "<li>Téléphone : " . $DateCom . "</li>\n";
      }

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
      <h1><a href="index.php">AJOUTER UNE COMMANDES</a></h1>
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
      // S'il y a eu des erreurs ou si aucun appui sur le bouton "S'incrire" 
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
                <label for="Ncli">Nom </label>
                <input type="text" id="nom" name="nom" placeholder="Nom"  value="<?php echo $Ncom ?>" maxlength="5" required>
              </p>
              <p>
                <label for="Ncli">Ncli </label>
                <input type="text" id="Ncli" name="Ncli" placeholder="Ncli"  value="<?php echo $Ncli ?>" maxlength="100" required>
              </p>
              <p>
                <label for="DateCom">DateCom </label>
                <input type="DateCom" id="DateCom" name="DateCom" placeholder="DateCom"  value="<?php echo $DateCom ?>" maxlength="250" required>
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

