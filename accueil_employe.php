<?php include 'imports.php' ?>
<?php include 'session.php' ?>

<?php

$ok = false;
$commandes = [];
$avis = [];
$menus = [];
if (isset($_SESSION['id']) || isset($_SESSION['role'])) {
    if (($_SESSION['role'] == Utilisateur::USER_ROLE_EMPLOYE) || ($_SESSION['role'] == Utilisateur::USER_ROLE_ADMIN)) {
        $ok = true;
        $commandes = Commande::loadAllCommande($pdo);
        $avis = Avis::loadAvisAValider($pdo);
        $menus = Menu::loadMenus($pdo);
    }
}
?>

<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <link rel="stylesheet" href="styles/accueil_employe.css">
    <title>Accueil</title>
</head>

<body>

    <?php include 'header.php' ?>


    <!-- main -->
    <main>
        <?php if ($ok): ?>
            <div class="liste_voyages_container">
                <form method="POST" role="form">

                    <h1>Commandes</h1>
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
                                    echo "<tr><td colspan='10'>Aucune commande trouvée.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h6>Aucun commande trouvé !</h6>
                    <?php endif; ?>

                </form>
            </div>

            <div class="liste_voyages_container">
                <h1>Avis à valider</h1>
                <form method="POST" role="form">
                    <table>
                        <thead>
                            <tr>
                                <!-- <th></th> -->
                                <th></th>
                                <th>N° Commande</th>
                                <th>Client</th>
                                <th>Menu</th>
                                <th>Commentaire</th>
                                <th>Note</th>
                                <!-- <th>Statut</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($avis)) {
                                foreach ($avis as $value) {
                                    echo "<tr";
                                    if ($value->getNote() < MIN_NOTE_AVIS) {
                                        echo " class=\"avis_negatif\"";
                                    }
                                    echo ">";
                                    // echo "<td><input type='checkbox' name='voyages_selectionnes[]' value='" . htmlspecialchars($value['Avis_Id']) . "'></td>";
                                    echo "<td><a href=\"detail_avis.php?avisId=" . htmlspecialchars($value->getId()) . "\">Détail</a></td>";
                                    echo "<td>" . htmlspecialchars($value->getCommande()->getNumeroCommande()) . "</td>";
                                    echo "<td>" . htmlspecialchars($value->getSoumisPar()->getFullName()) . " (" . htmlspecialchars($value->getSoumisPar()->getEmail()) . ")</td>";
                                    echo "<td>" . htmlspecialchars($value->getCommande()->getMenu()->getNom()) . " (commandé le " . htmlspecialchars($value->getCommande()->getDateCommande()) . ")</td>";
                                    echo "<td>" . htmlspecialchars($value->getCommentaire()) . "</td>";
                                    echo "<td>" . htmlspecialchars($value->getNote()) . "</td>";
                                    // echo "<td>" . htmlspecialchars($value->getStatut()) . "</td>";
                        
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10'>Aucun avis trouvé.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                </form>
            </div>


            <div class="liste_voyages_container">
                <form method="POST" role="form">

                    <div class="menu_header">
                        <h1>Menus</h1>
                        <a class="btn btn-primary" href="creer_menu.php" role="button">+ Nouveau Menu</a>
                    </div>
                    <?php if (isset($menus) && (!empty($menus))): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nom</th>
                                    <th>Thème</th>
                                    <th>Régime</th>
                                    <th>Quantite_restante</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($menus)) {
                                    foreach ($menus as $menu) {
                                        echo "<tr>";
                                        // echo "<td><input type='checkbox' name='voyages_selectionnes[]' value='" . htmlspecialchars($voyage['Covoiturage_Id']) . "'></td>";
                                        echo "<td><a href=\"editer_menu.php?menuId=" . htmlspecialchars($menu->getId()) . "\">Détail</a></td>";
                                        echo "<td>" . $menu->getNom() . "</td>";
                                        echo "<td>" . $menu->getTheme() . "</td>";
                                        echo "<td>" . $menu->getRegime() . "</td>";
                                        echo "<td>" . $menu->getQuantite_restante() . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>Aucune commande trouvée.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h6>Aucun menu trouvé !</h6>
                    <?php endif; ?>

                </form>
            </div>
        <?php endif; ?>

    </main>

    <?php include 'footer.php' ?>

</body>

</html>