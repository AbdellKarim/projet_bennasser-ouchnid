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
$pseudo = "";
$passe = "";

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

if (isset($_POST['connecter'])) {
  //***************************
  // Clic sur le bouton "Se connecter" de valeur name="connecter"
  // Traitement du formulaire
  // 
  // Filtrage du contenu de $_POST et assignation à des variables locales
  // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
  // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
  $pseudo = htmlspecialchars($_POST['pseudo']);
  $passe = trim($_POST['passe']);

  // Vérification de toutes les valeurs saisies
  // 
  if (empty($pseudo)) {
    $message_erreur .= "Le champ pseudo est obligatoire<br>\n";
  }

  if (empty($passe)) {
    $message_erreur .= "Le mot de passe est obligatoire<br>\n";
  }

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    //*******************************************
    // Verification que le pseudo et le mot de passe 
    // sont valides
    // 
    // Vérification que le pseudo existe dans la table utilisateur
    $requete = "select * from utilisateur where Pseudo = '$pseudo'";
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat
      if (mysqli_num_rows($resultat) != 0) {
        // Le pseudo existe bien dans la table
        // Récupération des informations de l'utlisateur
        $ligne = mysqli_fetch_assoc($resultat);

        // Vérification du mot de passe saisi
        if (password_verify($passe, $ligne['Password'])) {
          // Le mot de passe saisi est valide
          // -> Démarrage d'une session si cela n'a pas déjà été fait
          if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
          }

          // Initialisation des variables de session avec les données de l'utilisateur
          $_SESSION['session_idutilisateur'] = $ligne['IdUtilisateur'];
          $_SESSION['session_pseudo'] = $ligne['Pseudo'];
          $_SESSION['session_nom'] = $ligne['Nom'];
          $_SESSION['session_prenom'] = $ligne['Prenom'];

          // Redirection vers la page index.php
          header('Location: index.php');

          // Fin du script au cas où la redirection n'ait pas pu se faire
          exit();
        } else {
          // Le mot de passe saisi n'est pas valide
          $message_erreur .= "Connexion impossible<br>\n";
          $message_erreur .= "Le mot de passe n'est pas valide<br>\n";
        }
      } else {
        // Le pseudo n'existe pas dans la table
        $message_erreur .= "Connexion impossible<br>\n";
        $message_erreur .= "Le pseudo $pseudo n'existe pas<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête $requete<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
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
    <title>Connexion</title>
  </head>
  <body>
    <header>
      <h1><a href="index.php">TP n°6 : Authentification en PHP</a></h1>
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
      // S'il y a eu des erreurs ou si aucun appui sur le bouton "Se connecter" 
      if (!empty($message_erreur) || !isset($_POST['connecter'])) {
        ?>
        <!-- **************************************** -->
        <!-- Affichage du formulaire                  -->
        <section>     
          <h2>Connexion</h2>
          <form action="" method="POST">
            <section>
              <p>
                <label for="pseudo">Login </label>
                <input type="text" id="pseudo" name="pseudo" placeholder="Votre pseudo" value="<?php echo $pseudo ?>" minlength="5" maxlength="10" required>
              </p>  
              <p>
                <label for="passe">Mot de passe </label>
                <input type="password" id="passe" name="passe" placeholder="Votre mot de passe" value="" minlength="6" required>
              </p>  
            </section>
            <section>
              <p><input type="submit" name="connecter" value="Se connecter"></p>
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