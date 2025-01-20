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
    $requete = "SELECT * FROM `commande`";
  
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
                                  <th>Ncom</th>
                                  <th>Ncli</th> 
                                  <th>DateCom</th>
                                  </tr>\n";
        $tableau_ouvrages .= "</thead>\n
                            <tbody>\n";
        // Récupération des lignes du résultat de la requête
        while ($ligne = mysqli_fetch_assoc($resultat)) {
          $tableau_ouvrages .=
                "<tr>
                    <td>" . $ligne['Ncom'] . " </td>".
                   "<td>" . $ligne['Ncli'] . "</td>" .
                   "<td>" . $ligne['DateCom'] . "</td>".
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
    <title>Liste des Commandes</title>
    <link rel="stylesheet" href=""> <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <h1>Liste des Commandes</h1>
    <table border="1">
        <thead>
            <tr>

            </tr>
        </thead>

    </table>
    <p><a href="../UTILISATEURS/logout.php">Se déconnecter</a></p>
</body>
</html>

