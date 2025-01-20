
<?php require '../header.php';



$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur à afficher
$message_erreur = "";

// Gestion d'erreur manuelle : désactivation des rapports d'erreur
error_reporting(0); // Désactivation du rapport d'erreurs de PHP
mysqli_report(MYSQLI_REPORT_OFF); // Désactivation du rapport d'erreur mysqli
// Connexion à la base de données bibliotheque du serveur localhost
$connexion = mysqli_connect("localhost", "root", "", "clicom");
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
    $requete = "SELECT * FROM `produit`";
  
    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat
      if (mysqli_num_rows($resultat) == 0) {
        // La table ouvrage est vide
        $tableau_ouvrages .= "Aucun ouvrage<br>\n";
      } else {
        // Construction du tableau affichant le résultat de la requête
        $tableau_ouvrages .= "<table>\n
                              <thead>\n";
        $tableau_ouvrages .= "<tr>
                                  <th>NPro</th>
                                  <th>Libelle</th> 
                                  <th>PrixHT</th>
                                  <th>QStock</th>
                                  </tr>\n";
        $tableau_ouvrages .= "</thead>\n
                            <tbody>\n";
        // Récupération des lignes du résultat de la requête
        while ($ligne = mysqli_fetch_assoc($resultat)) {
          $tableau_ouvrages .=
                "<tr>
                    <td>" . $ligne['NPro'] . " </td>".
                   "<td>" . $ligne['Libelle'] . "</td>" .
                   "<td>" . $ligne['PrixHT'] . "</td>".
                   "<td>" . $ligne['QStock'] . "</td>".
               "</tr>\n";


        }
        // Fin du tableau affichant le résultat de la requête
        $tableau_ouvrages .= "</tbody>\n</table>\n";
      }	
      
    } else {
      $message_erreur .= "Erreur de la requête $requete<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }
  }
  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/CSS/header.css">
</head>
<body>




    <header>
        <h1>Bienvenue</h1>
    </header>
    <main>
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
    </main>
    <footer>
        <p>Pied de page</p>
    </footer>
</body>
</html>

<?php require '../fotter.php' ?>


<style>
  table {
      width: 60%;
      border-collapse: collapse;
      margin: 20px auto;
      font-family: Arial, sans-serif;
      font-size: 14px;
  }

  th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
  }

  thead {
      background-color: #f2f2f2;
      font-weight: bold;
  }

  tr:nth-child(even) {
      background-color: #f9f9f9;
  }

  tr:hover {
      background-color: #eaeaea;
  }
</style>
