




<?php require '../header.php'?>


<?php

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
    $requete = "SELECT * FROM `client`";
  
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
                                  <th>ISBN</th>
                                  <th>Titre</th> 
                                  <th>Prenom</th>
                                  <th>Adresse</th>
                                  <th>CP</th>
                                  <th>Ville</th>
                                  <th>CAT</th>
                                  <th>Compte</th>
                                  </tr>\n";
        $tableau_ouvrages .= "</thead>\n
                            <tbody>\n";
        // Récupération des lignes du résultat de la requête
        while ($ligne = mysqli_fetch_assoc($resultat)) {
          $tableau_ouvrages .=
                "<tr>
                    <td>" . $ligne['NCli'] . " </td>".
                   "<td>" . $ligne['Nom'] . "</td>" .
                   "<td>" . $ligne['Prenom'] . "</td>".
                   "<td>" . $ligne['Adresse'] . "</td>".
                   "<td>" . $ligne['CP'] . "</td>".
                   "<td>" . $ligne['Ville'] . "</td>".
                   "<td>" . $ligne['CAT'] . "</td>".
                   "<td>" . $ligne['Actions'] . "</td>".
                   "<td>" . $ligne['Compte'] . "</td>
                </tr>\n";


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
    <link rel="stylesheet" href="../CSS/header.css">
    <title>Liste des clients</title>
</head>
<body>
    <h1>Liste des clients</h1>
   <!-- <a href="ajouter.php">Ajouter un client</a>  -->

    <table border="1">


    </table>

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

</body>
</html>
  <?php require '../fotter.php' ?>


  
  <style>
  table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      font-size: 14px;
  }

  thead {
      background-color: #4CAF50;
      color: white;
  }

  th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
  }

  tr:nth-child(even) {
      background-color: #f2f2f2;
  }

  tr:hover {
      background-color: #ddd;
  }

  th {
      text-transform: uppercase;
      letter-spacing: 1px;
  }

  td {
      color: #333;
  }

  .actions {
      text-align: center;
  }

  .actions button {
      background-color: #2196F3;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 3px;
  }

  .actions button:hover {
      background-color: #0b7dda;
  }
</style>