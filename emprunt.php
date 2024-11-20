<?php
// **********************************************
// Traitement du formulaire
// 
// La variable $message contiendra les messages à afficher
$message = "";

// La variable $message_erreur contiendra les éventuels messages d'erreur à afficher
$message_erreur = "";

// Initialisation des variables utilisées pour construire le formulaire
$liste_deroulante_utilisateurs = "";
$liste_deroulante_exemplaires_disponibles = "";

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

if (isset($_POST['soumettre'])) {
  //***************************
  // Bouton "Valider" de valeur name="soumettre"
  // Traitement du formulaire

  $idempr = trim(htmlspecialchars($_POST['idempr']));
  $numero = trim(htmlspecialchars($_POST['numero']));
  $dateempr = htmlspecialchars($_POST['dateempr']);

  // Vérification de la validité des valeurs saisies 
  if (empty($idempr)) {
    $message_erreur .= "L'identifiant de l'emprunteur est obligatoire<br>\n";
  } elseif (!ctype_digit($idempr)) {
    $message_erreur .= "L'identifiant $idempr n'est pas valide<br>\n";
  }

  if (empty($numero)) {
    $message_erreur .= "Le numéro de l'exemplaire est obligatoire<br>\n";
  } elseif (!ctype_digit($numero)) {
    $message_erreur .= "Le numéro de l'exemplaire $numero n'est pas valide<br>\n";
  }

  if (empty($dateempr)) {
    $message_erreur .= "La date est obligatoire<br>\n";
  } elseif (!date_create($dateempr)) {
    $message_erreur .= "Le format de la date $dateempr n'est pas valide<br>\n";
  }

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    // Vérification que le numero existe dans la table Exemplaire
    // et qu'il n'est pas déjà emprunté
    $requete = "select * from exemplaire where Numero = $numero and IdEmpr is NULL;";
    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat de la requête
      $nbligne = mysqli_num_rows($resultat);
      if ($nbligne == 1) {
        // Le numéro d'exemplaire existe et n'est pas emprunté
        // Vérification que le numéro d'emprunteur existe dans la table utilisateur
        $requete = "select * from utilisateur where IdUtilisateur = $idempr;";
        // Exécution de la requête
        $resultat = mysqli_query($connexion, $requete);
        if ($resultat) {
          // Vérification du nombre de lignes du résultat de la requête
          $nbligne = mysqli_num_rows($resultat);
          if ($nbligne == 1) {
            // Le numéro d'utilisateur existe
            // 
            // Modification de l'exemplaire emprunté
            // -> emprunt de l'exemplaire : passage de NULL à $idempr et $dateempr pour IdUtilisateur et DateEmpr de exemplaire
            $requete = "update exemplaire set IdEmpr = $idempr, " .
                    "DateEmpr = '$dateempr' where " .
                    "exemplaire.Numero = $numero;";
            $resultat = mysqli_query($connexion, $requete);
            if ($resultat) {
              // La requête a été exécutée avec succès                                            
              $message .= "<p>Nous avons pris en compte le nouvel emprunt.<br>\n";
              $message .= "Voici les données qui ont été saisies : </p><ul>";
              $message .= "<li>Numéro de l'exemplaire emprunté : $numero</li>";
              $message .= "<li>Numéro de l'emprunteur : $idempr</li>";
              $message .= "<li>Date de l'emprunt : $dateempr</li></ul>";
            } else {
              $message_erreur .= "Erreur de la requête<br>\n";
              $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
            }
          } else {
            $message_erreur .= "Le numéro d'emprunteur $idempr n'existe pas<br>\n";
          }
        } else {
          $message_erreur .= "Erreur de la requête<br>\n";
          $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
        }
      } else {
        $message_erreur .= "Le numéro d'exemplaire $numero n'existe pas, ou l'exemplaire est déjà emprunté<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }
  } else {
    $message_erreur .= "<a href=''>Retour au formulaire<br>\n";
  }
  /*   * ****************************** */
} else {
  if (empty($message_erreur)) {
    // Création de la liste déroulante des utilisateurs
    $requete = "select IdUtilisateur, "
            . "concat(Nom,' ',Prenom,' (',IdUtilisateur,')') as NomPrenom "
            . "from utilisateur order by NomPrenom;";
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      $nbligne = mysqli_num_rows($resultat);
      if ($nbligne != 0) {
        while ($utilisateur = mysqli_fetch_assoc($resultat)) {
          $liste_deroulante_utilisateurs .= "<option value=" . $utilisateur['IdUtilisateur'] . ">" . $utilisateur['NomPrenom'] . "</option>\n";
        }
      } else {
        // Pas d'utilisateur dans la base
        $message_erreur .= "Aucun utilisateur saisi dans la base<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }

    // Création de la liste déroulante des exemplaires disponibles
    // à partir de la vue SQL exemplaires_titre_auteurs_disponibles
    // ATTENTION : Il faut exécuter "cr_vue_exemplaires_titre_auteurs_disponibles.sql"
    // avant d'exécuter ce scipt
    $requete = "select * from exemplaires_titre_auteurs_disponibles";
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      $nbligne = mysqli_num_rows($resultat);
      if ($nbligne == 0) {
        // Pas d'exemplaire disponible dans la base
        $message_erreur .= "Aucun exemplaire disponible<br>\n";
      } else {
        while ($exemplaire = mysqli_fetch_assoc($resultat)) {
          $liste_deroulante_exemplaires_disponibles .= "<option value=" . $exemplaire['Numero'] . ">" . $exemplaire['TitreAuteurs'] . " - Numéro " . $exemplaire['Numero'] . "</option>\n";
        }
      }
    } else {
      $message_erreur .= "Erreur de la requête<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }
  }
}

// Déconnexion de la base de données
if ($connexion) {
  mysqli_close($connexion);
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
      // S'il y a eu des erreurs ou si aucun appui sur le bouton "Valider"
      if (!empty($message_erreur) || !isset($_POST['soumettre'])) {
        ?> 
        <!-- **************************************** -->
        <!-- Affichage du formulaire                  -->
        <section>     
          <h2>Emprunt d'un livre</h2>
          <form action="" method="POST">
            <p>
              <label for="edit-utilisateur">Emprunteur : </label>
              <select id="edit-utilisateur" name="idempr"><?php echo $liste_deroulante_utilisateurs; ?></select>
            </p>
            <p>
              <label for="edit-exemplaire">Exemplaire : </label>
              <select id="edit-exemplaire" name="numero"><?php echo $liste_deroulante_exemplaires_disponibles; ?></select>
            </p>
            <p>
              <label for="edit-date">Date de l'emprunt : </label>
              <input type="date" id="edit-date" name="dateempr" value="<?php echo date('Y-m-d'); ?>" required>
            </p>
            <p>
              <?php if(!empty($liste_deroulante_exemplaires_disponibles) && !empty($liste_deroulante_utilisateurs)) { ?>
              <input type="submit" name="soumettre" value="Valider">&nbsp;<input type="reset" value="Annuler"> &nbsp;
              <?php } else { ?>
                <input type="submit" name="soumettre" value="Valider" disabled>&nbsp;<input type="reset" value="Annuler" disabled> &nbsp;
              <?php } ?>
            </p>
          </form>
        </section>
        <?php
      }
      ?>
    </main>
<?php require 'fotter.php' ?>
