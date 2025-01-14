


<?php require 'header.php' ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/header.css">
</head>


    <main>
      <section>
    
     
     <h2>Projet </h2>


      </section>
    </main>


</html>


<?php
// Paramètres de connexion
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clicom";

try {
    // Créer une connexion PDO au serveur MySQL (pas encore à la base de données)
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // Définir le mode d'erreur PDO pour afficher les exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si la base de données existe
    $sql = "SHOW DATABASES LIKE '$dbname'";
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() == 0) {
        // Si la base de données n'existe pas, la créer
        $create_db_sql = "CREATE DATABASE $dbname";
        $conn->exec($create_db_sql);
        echo "La base de données '$dbname' a été créée avec succès.<br>";
    } else {
        echo "La base de données '$dbname' existe déjà.<br>";
    }

    // Sélectionner la base de données
    $conn->exec("USE $dbname");

    // Définir les requêtes pour créer les tables
    $table_sql = [
        "CREATE TABLE IF NOT EXISTS Utilisateurs (
            IdUtilisateur INT AUTO_INCREMENT PRIMARY KEY,
            Sexe ENUM('H', 'F', 'M') DEFAULT 'H',
            Nom VARCHAR(255) NOT NULL,
            Prenom VARCHAR(255) NOT NULL,
            Telephone VARCHAR(20),
            Email VARCHAR(255) UNIQUE NOT NULL,
            Pseudo VARCHAR(255) UNIQUE NOT NULL,
            Password VARCHAR(255) NOT NULL,
            AboNewsletter TINYINT(1) DEFAULT 0,
            Commentaire TEXT
        )",

        "CREATE TABLE IF NOT EXISTS Clients (
            IdClient INT AUTO_INCREMENT PRIMARY KEY,
            Nom VARCHAR(255) NOT NULL,
            Prenom VARCHAR(255) NOT NULL,
            Email VARCHAR(255) UNIQUE NOT NULL,
            Telephone VARCHAR(20),
            Adresse TEXT,
            DateInscription DATETIME DEFAULT CURRENT_TIMESTAMP,
            IdUtilisateur INT,
            FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur) ON DELETE SET NULL
        )",

        "CREATE TABLE IF NOT EXISTS Produits (
            IdProduit INT AUTO_INCREMENT PRIMARY KEY,
            NomProduit VARCHAR(255) NOT NULL,
            Description TEXT,
            Prix DECIMAL(10, 2) NOT NULL,
            Stock INT DEFAULT 0
        )",

        "CREATE TABLE IF NOT EXISTS Commandes (
            IdCommande INT AUTO_INCREMENT PRIMARY KEY,
            IdClient INT NOT NULL,
            IdUtilisateur INT,
            DateCommande DATETIME DEFAULT CURRENT_TIMESTAMP,
            TotalCommande DECIMAL(10, 2),
            Statut ENUM('En attente', 'Validée', 'Expédiée', 'Annulée') DEFAULT 'En attente',
            FOREIGN KEY (IdClient) REFERENCES Clients(IdClient) ON DELETE CASCADE,
            FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateurs(IdUtilisateur) ON DELETE SET NULL
        )",

        "CREATE TABLE IF NOT EXISTS DetailsCommande (
            IdDetail INT AUTO_INCREMENT PRIMARY KEY,
            IdCommande INT NOT NULL,
            IdProduit INT NOT NULL,
            Quantite INT NOT NULL,
            PrixUnitaire DECIMAL(10, 2),
            FOREIGN KEY (IdCommande) REFERENCES Commandes(IdCommande) ON DELETE CASCADE,
            FOREIGN KEY (IdProduit) REFERENCES Produits(IdProduit) ON DELETE CASCADE
        )"
    ];
    //cam
    // Exécuter les requêtes pour créer les tables si elles n'existent pas
    foreach ($table_sql as $sql) {
        $conn->exec($sql);
        echo "Table créée ou déjà existante.<br>";
    }

} catch (PDOException $e) {
    // En cas d'erreur de connexion ou d'exécution, afficher l'erreur
    echo "Erreur: " . $e->getMessage();
}

// Fermer la connexion PDO
$conn = null;
?>






