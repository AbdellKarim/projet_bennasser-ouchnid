<?php
// La variable $message contiendra les messages à afficher
$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur à afficher
$message_erreur = "";

// Gestion d'erreur manuelle : désactivation des rapports d'erreur
error_reporting(0); // Désactivation du rapport d'erreurs de PHP
mysqli_report(MYSQLI_REPORT_OFF); // Désactivation du rapport d'erreur mysqli
// Connexion à la base de données bibliotheque du serveur localhost
$connexion = mysqli_connect("localhost", "root", "", "bibliotheque");
if ($connexion) {
  $message .= "Connexion établie<br>\n";
  // Changement du jeu de caractères pour utf-8 
  mysqli_set_charset($connexion, "utf8");
} else {
  $message_erreur .= "Erreur de connexion<br>\n";
  $message_erreur .= "  Erreur n° " . mysqli_connect_errno() . " : " . mysqli_connect_error() . "<br>\n";
}

// Déconnexion de la base de données bibliotheque
if ($connexion) {
  mysqli_close($connexion);
  $message .= "Déconnexion de la base<br>";
}
?> 
<!doctype html>
<!-- **************************************** -->
<!-- Construction de la page HTML             --> 
<html>
  <head>
    <meta charset="UTF-8">
    <title>Connexion à la base de données</title>
  </head>
  <body>
    <header>
      <h1><a href="index.php">TP n°5 : Programmation MySQL en PHP</a></h1>
    </header>
    <nav>
      <!-- Insérer une barre de navigation ici -->
    </nav>
    <main>
      <?php
      if (!empty($message_erreur) || !empty($message)) {
        ?>
        <!-- **************************************** -->
        <!-- Messages de l'application                -->
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
      ?> 
    </main>
    <footer>
      <!-- Insérer un pied de page ici -->
    </footer>
  </body>
</html>