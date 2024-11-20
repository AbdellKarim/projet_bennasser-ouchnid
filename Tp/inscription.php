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
$sexe = "";
$civilite = "";
$checked_h = "";
$checked_f = "";
$nom = "";
$prenom = "";
$mail = "";
$telephone = "";
$pseudo = "";
$passe1 = "";
$passe2 = "";
$abo_newsletter = 0;
$checked_abonews = "";
$commentaire = "";

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

// Si aucun message d'erreur
if (empty($message_erreur)) {
// Démarrage d'une session si cela n'a pas déjà été fait
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }

// Vérification d'une éventuelle connexion d'un utilisateur
  if (isset($_SESSION['session_idutilisateur'])) {
    // Un utilisateur est connecté
    // -> Récupération de son identifiant dans les variables de session
    $session_idutilisateur = $_SESSION['session_idutilisateur'];

    // Récupération des données de l'utilisateur dans la table utilisateur
    // afin de préremplir le formaulaire
    // Vérification que le pseudo existe dans la table utilisateur
    $requete = "select * from utilisateur where IdUtilisateur = $session_idutilisateur";
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat
      if (mysqli_num_rows($resultat) != 0) {
        // L'identifiant existe bien dans la table
        // Récupération des informations de l'utlisateur
        $utilisateur = mysqli_fetch_assoc($resultat);

        $checked_h = (strcmp($utilisateur['Sexe'], "H") == 0) ? "checked" : "";
        $checked_f = (strcmp($utilisateur['Sexe'], "F") == 0) ? "checked" : "";
        $nom = $utilisateur['Nom'];
        $prenom = $utilisateur['Prenom'];
        $mail = $utilisateur['Mail'];
        $telephone = $utilisateur['Telephone'];
        $pseudo = $utilisateur['Pseudo'];
        $passe1 = "";
        $passe2 = "";
        $checked_abonews = ($utilisateur['AboNewsletter'] == 1) ? "checked" : "";
        $commentaire = $utilisateur['Commentaire'];
      } else {
        // L'identifiant n'existe pas !
        $message_erreur .= "L'identifiant $session_idutilisateur n'existe pas !<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête $requete<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }
  }
}

if (isset($_POST['inscrire']) || isset($_POST['modifier'])) {
  //***************************
  // Clic sur le bouton "S'inscrire" de valeur name="inscrire"
  // ou sur  le bouton "Modifier" de valeur name="modifier"
  // Traitement du formulaire
  // 
  // Filtrage du contenu de $_POST et assignation à des variables locales
  // htmlspecialchars() : Convertit les caractères spéciaux en entités HTML
  // trim() : Supprime les espaces (ou d'autres caractères) en début et fin de chaîne
  $nom = trim(htmlspecialchars($_POST['nom'], ENT_COMPAT));
  $prenom = trim(htmlspecialchars($_POST['prenom'], ENT_COMPAT));
  $mail = htmlspecialchars($_POST['mail']);
  $telephone = htmlspecialchars($_POST['telephone']);
  $pseudo = htmlspecialchars($_POST['pseudo']);
  $passe1 = trim($_POST['passe1']);
  $passe2 = trim($_POST['passe2']);
  $commentaire = htmlspecialchars($_POST['commentaire']);

  // Vérification de toutes les valeurs saisies
  // 
  // On vérifie si "Civilité" a été cochée
  if (isset($_POST['civilite'])) {
    $sexe = $_POST['civilite'];
    if ($sexe == 'H') {
      $checked_h = "checked";
      $civilite = "M";
    } else {
      $checked_f = "checked";
      $civilite = "Mme";
    }
  } else {
    $message_erreur .= "La civilité doit être cochée<br>\n";
  }

  if (empty($nom)) {
    $message_erreur .= "Le champ nom est obligatoire<br>\n";
  } elseif (strlen($nom) > 100) {
    $message_erreur .= "Le nom ne doit pas comporter plus de 100 caractères<br>\n";
  } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $nom)) {
    // [[:alpha:]] : caractères alphabétique
    // [[:space:]] : espace blanc
    $message_erreur .= "Le nom ne doit comporter que des lettres<br>\n";
  }

  if (empty($prenom)) {
    $message_erreur .= "Le champ prenom est obligatoire<br>\n";
  } elseif (strlen($prenom) > 100) {
    $message_erreur .= "Le prénom ne doit pas comporter plus de 100 caractères<br>\n";
  } elseif (!preg_match('/^([[:alpha:]]|-|[[:space:]]|\')*$/u', $prenom)) {
    $message_erreur .= "Le prénom ne doit comporter que des lettres<br>\n";
  }

  if (empty($mail)) {
    $message_erreur .= "Le champ mail est obligatoire<br>\n";
  } elseif (strlen($mail) > 250) {
    $message_erreur .= "Le champ mail doit être inférieur à 250 caractères<br>\n";
  } elseif (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $mail)) {
    $message_erreur .= "Le champ mail doit être valide mail@domaine.fr<br>\n";
  }

  if (empty($pseudo)) {
    $message_erreur .= "Le champ pseudo est obligatoire<br>\n";
  } elseif (strlen($pseudo) > 10 || strlen($pseudo) < 5) {
    $message_erreur .= "Le pseudo doit être composé de 5 à 10 caractères<br>\n";
  } elseif (!preg_match('/^[a-zA-Z0-9]*$/u', $pseudo)) {
    $message_erreur .= "Le pseudo ne doit comporter que des lettres non accentuées ou des chiffres et pas d'espaces<br>\n";
  }

  if (empty($passe1)) {
    $message_erreur .= "Le mot de passe est obligatoire<br>\n";
  } elseif (strlen($passe1) < 6) {
    $message_erreur .= "Le mot de passe doit contenir au moins 6 caractères<br>\n";
  } elseif (!preg_match('/^[[:graph:]]*$/u', $passe1)) {
    // [[:graph:]] : tous les caractères imprimables sauf l'espace
    $message_erreur .= "Le mot de passe ne doit pas comporter d'espaces<br>\n";
  }

  if (strcmp($passe1, $passe2) != 0) {
    $message_erreur .= "Les mots de passe sont différents<br>\n";
  }

  // On vérifie si la case "Abonnement à la newsletter" a été cochée
  if (isset($_POST['abo_newsletter'])) {
    $abo_newsletter = 1;
    $checked_abonews = "checked";
  } else {
    $abo_newsletter = 0;
  }

  // Cryptage du mot de passe
  $passe_chiffre = password_hash($passe1, PASSWORD_DEFAULT);

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    //*******************************************
    // Saisie des données du formulaire dans la table utilisateur
    // après verification que le pseudo et le mail n'existent 
    // pas déjà dans la table
    // 
    // Vérification que le pseudo n'existe pas dans la table utilisateur
    if (isset($session_idutilisateur)) {
      // Un utilisateur est connecté
      $requete = "select * from utilisateur where Pseudo = '$pseudo' and IdUtilisateur != $session_idutilisateur";
    } else {
      // Aucun utilisateur connecté        
      $requete = "select * from utilisateur where Pseudo = '$pseudo'";
    }
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat
      if (mysqli_num_rows($resultat) != 0) {
        // Le pseudo existe déjà dans la table
        $message_erreur .= "Le pseudo $pseudo existe déjà<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête $requete<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }

    // Vérification que le mail n'existe pas dans la table utilisateur
    if (isset($session_idutilisateur)) {
      // Un utilisateur est connecté
      $requete = "select * from utilisateur where Mail = '$mail' and IdUtilisateur != $session_idutilisateur";
    } else {
      // Aucun utilisateur connecté        
      $requete = "select * from utilisateur where Mail = '$mail'";
    }
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Vérification du nombre de lignes du résultat
      if (mysqli_num_rows($resultat) != 0) {
        // Le mail existe déjà dans la table
        $message_erreur .= "Le mail $mail existe déjà<br>\n";
      }
    } else {
      $message_erreur .= "Erreur de la requête $requete<br>\n";
      $message_erreur .= "Erreur n° " . mysqli_errno($connexion) . " : " . mysqli_error($connexion) . "<br>\n";
    }
  }

  // Si aucun message d'erreur
  if (empty($message_erreur)) {
    if (isset($session_idutilisateur)) {
      // Un utilisateur est connecté
      // -> il faut modifier les informations dans la table utilisateur
      // 
      // Requête de modification de l'utilisateur dans la table utilisateur
      $requete = "update utilisateur set "
              . "Sexe = '$sexe', "
              . "Nom = '$nom', "
              . "Prenom = '$prenom', "
              . "Mail = '$mail', "
              . "Telephone = ";
      $requete .= empty($telephone) ? "null, " : "'$telephone', ";
      $requete .= "Pseudo = '$pseudo', "
              . "Password = '$passe_chiffre', "
              . "AboNewsletter = $abo_newsletter, "
              . "Commentaire = ";
      $requete .= empty($commentaire) ? "null " : "'$commentaire' ";
      $requete .= "where IdUtilisateur = $session_idutilisateur;";
    } else {
      // Aucun utilisateur connecté
      // -> il faut insérer les donées du formulaire dans la table utilisateur
      // 
      // Requête d'insertion de l'utilisateur dans la table utilisateur
      $requete = "insert into utilisateur (Sexe,Nom,Prenom,Mail,Telephone,"
              . "Pseudo,Password,AboNewsletter,Commentaire)"
              . "values ('$sexe',\"$nom\",\"$prenom\",'$mail',";
      $requete .= empty($telephone) ? "null" : "'$telephone'";
      $requete .= ",'$pseudo','$passe_chiffre',$abo_newsletter,";
      $requete .= empty($commentaire) ? "null" : "\"$commentaire\"";
      $requete .= ");";
    }

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);
    if ($resultat) {
      // Affiche un message de confirmation ainsi que les valeurs saisies
      if (isset($session_idutilisateur)) {
        // Un utilisateur est connecté
        $message .= "    <p>Nous avons pris en compte votre demande de modification.\n";

        // Mise à jour des variables de session
        $_SESSION['session_pseudo'] = $pseudo;
        $_SESSION['session_nom'] = $nom;
        $_SESSION['session_prenom'] = $prenom;
      } else {
        // Aucun utilisateur connecté 
        $message .= "<p>Nous avons pris en compte votre inscription.\n";
      }
      $message .= "<br>Voici les données saisies :</p>\n";
      $message .= "<ul>\n";
      $message .= "<li>Civilité : " . $civilite . "</li>\n";
      $message .= "<li>Nom : " . $nom . "</li>\n";
      $message .= "<li>Prénom : " . $prenom . "</li>\n";
      $message .= "<li>Mail : " . $mail . "</li>\n";
      if (empty($telephone)) {
        $message .= "<li>Téléphone : Non saisi</li>\n";
      } else {
        $message .= "<li>Téléphone : " . $telephone . "</li>\n";
      }
      $message .= "<li>Pseudo : " . $pseudo . "</li>\n";
      $message .= "<li>Mot de passe 1 : " . $passe1 . "</li>\n";
      $message .= "<li>Mot de passe 2 : " . $passe2 . "</li>\n";
      $message .= "<li>Inscription à la newsletter : ";
      if ($abo_newsletter == 1) {
        $message .= "Oui</li>\n";
      } else {
        $message .= "Non</li>\n";
      }
      $message .= "<li>Commentaire : ";
      if (empty($commentaire)) {
        $message .= "Aucun</li>\n";
      } else {
        $message .= "\n<blockquote>" . nl2br($commentaire, false) . "</blockquote></li>\n";
      }
      $message .= "    </ul>\n";
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
    <title>Inscription</title>
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

      // S'il y a eu des erreurs ou si aucun appui sur le bouton "S'incrire" ou le bouton "Modifier" 
      if (!empty($message_erreur) || !(isset($_POST['inscrire']) || isset($_POST['modifier']))) {
        ?>
        <!-- **************************************** -->
        <!-- Affichage du formulaire                  -->
        <section>     
          <h2>Inscription</h2>
          <form action="" method="POST">
            <section>
              <h3>Coordonnées</h3>
              <p>
                <label for="m">Civilité</label>
                <label for="m"><input type="radio" id="m" name="civilite" value="H" <?php echo $checked_h ?>> M</label>
                <label for="mme"><input type="radio" id="mme" name="civilite" value="F" <?php echo $checked_f ?>> Mme</label>
              </p>
              <p>
                <label for="nom">Nom </label>
                <input type="text" id="nom" name="nom" placeholder="Nom"  value="<?php echo $nom ?>" maxlength="100" required>
              </p>
              <p>
                <label for="prenom">Prénom </label>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom"  value="<?php echo $prenom ?>" maxlength="100" required>
              </p>
              <p>
                <label for="mail">Adresse Mail </label>
                <input type="email" id="mail" name="mail" placeholder="Adresse Mail"  value="<?php echo $mail ?>" maxlength="250" required>
              </p>
              <p>
                <label for="telephone">Numéro de téléphone </label>
                <input type="tel" id="telephone" name="telephone" placeholder="Numéro de téléphone (facultatif)"  value="<?php echo $telephone ?>" maxlength="50">
              </p>
            </section>
            <section>
              <h3>Informations de connexion</h3>
              <p>
                <label for="pseudo">Pseudo </label>
                <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" value="<?php echo $pseudo ?>" minlength="5" maxlength="10" required>
              </p>  
              <p>
                <label for="passe1">Mot de passe </label>
                <input type="password" id="passe1" name="passe1" placeholder="Mot de passe" value="" minlength="6" required>
              </p>  
              <p>
                <label for="passe2">Confirmer le mot de passe </label>
                <input type="password" id="passe2" name="passe2" placeholder="Mot de passe" value="" minlength="6" required>
              </p>
            </section>
            <section>
              <h3>Divers</h3>
              <p>
                <input type="checkbox" id="abo_newsletter" name="abo_newsletter" value="" <?php echo $checked_abonews ?>>
                <label for="abo_newsletter">Abonnement à la newsletter</label>
              </p>
              <p>
                <label for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" placeholder="Laissez un commentaire" rows="2"><?php echo $commentaire ?></textarea>
              </p>
            </section>
            <section>
              <?php if (!isset($session_idutilisateur)) { ?>
                <!-- Aucun utilisateur connecté -->
                <p><input type="submit" name="inscrire" value="S'inscrire"></p>
              <?php } else { ?>
                <!-- Un utilisateur est connecté -->
                <p><input type="submit" name="modifier" value="Modifier"></p>
              <?php } ?>
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