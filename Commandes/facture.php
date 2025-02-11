<?php
require '../auth.php'; // Vérifier si l'utilisateur est connecté
require '../header.php'; // Inclusion du header
require '../SCRIPTS/DB.php'; // Connexion à la base de données

// Récupérer tous les clients pour les afficher dans la liste déroulante
$requete_clients = "SELECT NCli, Nom, Prenom FROM client ORDER BY Nom";
$resultat_clients = mysqli_query($connexion, $requete_clients);

$client_selectionne = false;
$facture_affichee = false;

// Vérifier si un client a été sélectionné
if (isset($_POST['client_id']) && !empty($_POST['client_id'])) {
    $NCli = htmlspecialchars($_POST['client_id']);
    $client_selectionne = true;

    // Vérifier si le client a des commandes
    $requete_commandes = "SELECT NCom, DateCom FROM commande WHERE NCli = ?";
    $stmt = mysqli_prepare($connexion, $requete_commandes);
    mysqli_stmt_bind_param($stmt, "s", $NCli);
    mysqli_stmt_execute($stmt);
    $resultat_commandes = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultat_commandes) > 0) {
        $facture_affichee = true;
    } else {
        echo "<h2 style='color:red; text-align:center;'>⚠️ Ce client n'a aucune commande.</h2>";
    }
}

// Si une commande spécifique a été sélectionnée
if (isset($_POST['commande_id']) && !empty($_POST['commande_id'])) {
    $NCom = htmlspecialchars($_POST['commande_id']);

    // Récupérer les infos du client et de la commande
    $requete = "SELECT c.NCom, c.DateCom, cl.NCli, cl.Nom, cl.Prenom, cl.Adresse, cl.Ville, cl.CP
                FROM commande c
                JOIN client cl ON c.NCli = cl.NCli
                WHERE c.NCom = ?";

    $stmt = mysqli_prepare($connexion, $requete);
    mysqli_stmt_bind_param($stmt, "i", $NCom);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);

    if ($commande = mysqli_fetch_assoc($resultat)) {
        // Récupération des détails de la commande
        $requete_detail = "SELECT p.NPro, p.Libelle, p.PrixHT, d.QCom
                           FROM detail d
                           JOIN produit p ON d.NPro = p.NPro
                           WHERE d.NCom = ?";

        $stmt_detail = mysqli_prepare($connexion, $requete_detail);
        mysqli_stmt_bind_param($stmt_detail, "i", $NCom);
        mysqli_stmt_execute($stmt_detail);
        $resultat_detail = mysqli_stmt_get_result($stmt_detail);

        $totalHT = 0;
        $tva = 0.2; // TVA à 20%

        $tableau_details = "<table>
                                <thead>
                                    <tr>
                                        <th>Réf Produit</th>
                                        <th>Libellé</th>
                                        <th>Prix Unitaire HT</th>
                                        <th>Quantité</th>
                                        <th>Total HT</th>
                                    </tr>
                                </thead>
                                <tbody>";

        while ($ligne = mysqli_fetch_assoc($resultat_detail)) {
            $totalLigne = $ligne['PrixHT'] * $ligne['QCom'];
            $totalHT += $totalLigne;

            $tableau_details .= "<tr>
                                    <td>{$ligne['NPro']}</td>
                                    <td>{$ligne['Libelle']}</td>
                                    <td>{$ligne['PrixHT']} €</td>
                                    <td>{$ligne['QCom']}</td>
                                    <td>{$totalLigne} €</td>
                                </tr>";
        }

        $tableau_details .= "</tbody></table>";

        $totalTVA = $totalHT * $tva;
        $totalTTC = $totalHT + $totalTVA;

        $facture_affichee = true;
    } else {
        echo "<h2 style='color:red; text-align:center;'>⚠️ Commande introuvable !</h2>";
    }
}

// Déconnexion de la base de données
mysqli_close($connexion);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <style>
        .facture-container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section p {
            font-size: 18px;
            font-weight: bold;
        }
        .btn-imprimer {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .btn-imprimer:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Facture</h1>
    </header>

    <main class="facture-container">
        <h2>Sélectionnez un client</h2>
        <form method="POST">
            <label for="client_id">Client :</label>
            <select name="client_id" id="client_id" required>
                <option value="">-- Choisissez un client --</option>
                <?php while ($client = mysqli_fetch_assoc($resultat_clients)) { ?>
                    <option value="<?= $client['NCli']; ?>" <?= $client_selectionne && $client['NCli'] == $NCli ? 'selected' : ''; ?>>
                        <?= $client['Prenom'] . " " . $client['Nom']; ?>
                    </option>
                <?php } ?>
            </select>
            <input type="submit" value="Rechercher Commandes">
        </form>

        <?php if ($facture_affichee && $client_selectionne) { ?>
            <h2>Sélectionnez une commande</h2>
            <form method="POST">
                <input type="hidden" name="client_id" value="<?= $NCli; ?>">
                <label for="commande_id">Commande :</label>
                <select name="commande_id" id="commande_id" required>
                    <option value="">-- Choisissez une commande --</option>
                    <?php while ($commande = mysqli_fetch_assoc($resultat_commandes)) { ?>
                        <option value="<?= $commande['NCom']; ?>">
                            Commande N° <?= $commande['NCom']; ?> - <?= $commande['DateCom']; ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="submit" value="Afficher la Facture">
            </form>
        <?php } ?>

        <?php if ($facture_affichee) { ?>
            <?= $tableau_details; ?>
            <div class="total-section">
                <p><strong>Total HT :</strong> <?= number_format($totalHT, 2, ',', ' ') ?> €</p>
                <p><strong>TVA (20%) :</strong> <?= number_format($totalTVA, 2, ',', ' ') ?> €</p>
                <p><strong>Total TTC :</strong> <?= number_format($totalTTC, 2, ',', ' ') ?> €</p>
            </div>
            <a href="#" class="btn-imprimer" onclick="window.print(); return false;">Imprimer la Facture</a>
        <?php } ?>

    </main>
    <?php require '../fotter.php'; ?>
</body>

</html>
