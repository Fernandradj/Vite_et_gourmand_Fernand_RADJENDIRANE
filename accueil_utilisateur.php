<?php include 'imports.php' ?>
<?php include 'session.php' ?>

<?php

$ok = false;
if (isset($_SESSION['id']) || isset($_SESSION['role'])) {
    if ($_SESSION['role'] == Utilisateur::USER_ROLE_UITILISATEUR) {
        $ok = true;
        $commandes = Commande::loadCommandeUtilisateur($_SESSION['id'], $pdo);
    }
}
?>
<?php
if (isset($_POST['action']) && isset($_POST['voyages_selectionnes'])) {
    // $action = $_POST['action'];
    // $voyages_selectionnes = $_POST['voyages_selectionnes'];

    // // Convertir le tableau en une chaîne pour la clause IN de la requête SQL
    // $placeholders = implode(',', array_fill(0, count($voyages_selectionnes), '?'));


    /* try {

        if ($action === 'valider') {
            // Logique pour valider les voyages sélectionnés
            $stmt = $pdo->prepare("UPDATE voyages SET Statut = 'Validé' WHERE ID_voyage IN ($placeholders)");
            $stmt->execute($voyages_selectionnes);
            echo "Les voyages sélectionnés ont été validés.";

        } elseif ($action === 'annuler') {
            // Logique pour annuler les voyages sélectionnés
            $stmt = $pdo->prepare("UPDATE voyages SET Statut = 'Annulé' WHERE ID_voyage IN ($placeholders)");
            $stmt->execute($voyages_selectionnes);
            echo "Les voyages sélectionnés ont été annulés.";
        }

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    } */
} else {
    // echo "Aucune action ou aucun voyage sélectionné.";
}
?>


<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <title>Vite et Gourmand</title>
</head>

<body>

    <?php include 'header.php' ?>


    <!-- main -->
    <main>
        <?php if ($ok): ?>
        <div class="liste_voyages_container">
            <form action="traitement.php" method="post">

                <h1>Mes commandes</h1>
                <?php if (isset($commandes) && (!empty($commandes))): ?>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>N° Commande</th>
                            <th>Date/Heure livraison</th>
                            <th>Statut</th>
                            <th>Menu</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                if (!empty($commandes)) {
                                    foreach ($commandes as $cmd) {
                                        echo "<tr>";
                                        // echo "<td><input type='checkbox' name='voyages_selectionnes[]' value='" . htmlspecialchars($voyage['Covoiturage_Id']) . "'></td>";
                                        echo "<td><a href=\"commande.php?commandeId=" . htmlspecialchars($cmd->getNumeroCommande()) . "\">Détail</a></td>";
                                        echo "<td>" . $cmd->getNumeroCommande() . "</td>";
                                        echo "<td>" . $cmd->getDateHeureLivraison() . "</td>";
                                        echo "<td>" . $cmd->getStatut() . "</td>";
                                        echo "<td>" . $cmd->getMenuNom() . "</td>";
                                        echo "<td>" . $cmd->getPrixTotale() . " €</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>Aucun voyage trouvé.</td></tr>";
                                }
                                ?>
                    </tbody>
                </table>
                <?php else: ?>
                <h6>Aucun commande trouvé !</h6>
                <?php endif; ?>

            </form>
        </div>
        <?php endif; ?>
    </main>

    <?php include 'footer.php' ?>

</body>

</html>