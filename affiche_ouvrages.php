<?php
// La variable $message contiendra les messages à afficher
$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur à afficher
$message_erreur = "";

// La variable $tableau_ouvrages contiendra le tableau des ouvrages à afficher
$tableau_ouvrages = "";

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

// Si aucun message d'erreur
if (empty($message_erreur)) {
  // Affichage du contenu de la table ouvrage dans un tableau
  // Requête d'affichage de la table ouvrage
  $requete = "select * from ouvrage order by Titre desc";

  // Exécution de la requête
  $resultat = mysqli_query($connexion, $requete);
  if ($resultat) {
    // Vérification du nombre de lignes du résultat
    if (mysqli_num_rows($resultat) == 0) {
      // La table ouvrage est vide
      $tableau_ouvrages .= "Aucun ouvrage<br>\n";
    } else {
      // Construction du tableau affichant le résultat de la requête
      $tableau_ouvrages .= "<table>\n<thead>\n";
      $tableau_ouvrages .= "<tr><th>ISBN</th><th>Titre</th></tr>\n";
      $tableau_ouvrages .= "</thead>\n<tbody>\n";
      // Récupération des lignes du résultat de la requête
      while ($ligne = mysqli_fetch_assoc($resultat)) {
        $tableau_ouvrages .= "<tr><td>" . $ligne['ISBN'] . " </td>"
                . "<td>" . $ligne['Titre'] . "</td></tr>\n";
      }
      // Fin du tableau affichant le résultat de la requête
      $tableau_ouvrages .= "</tbody>\n</table>\n";
    }
  } else {
    $message_erreur .= "Erreur de la requête $requete<br>\n";
    $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
  }
}

// Déconnexion de la base de données
if ($connexion) {
  mysqli_close($connexion);
  $message .= "Déconnexion de la base<br>";
}
?> 
<?php require 'header.php' ?>
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
      <?php
      if (!empty($tableau_ouvrages)) {
        ?>
        <!-- **************************************** -->
        <!-- Affichage de la table ouvrage            -->
        <section>
          <h2>Ouvrages</h2>
          <?php
          echo $tableau_ouvrages;
          ?>
        </section>          
        <?php
      }
      ?>
    </main>
<?php require 'fotter.php' ?>