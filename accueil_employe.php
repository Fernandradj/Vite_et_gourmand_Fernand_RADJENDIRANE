<?php include 'imports.php' ?>
<?php include 'session.php' ?>

<?php


if (!isset($_SESSION['labels'])) {
    $_SESSION['labels'] = [];
}

$ok = false;
$commandes = [];
$avis = [];
$menus = [];
$avisDAO = new AvisDAO($pdo);
$commandeDAO = new CommandeDAO($pdo);
$menuDAO = new MenuDAO($pdo);
if (isset($_SESSION['id']) || isset($_SESSION['role'])) {
    if (($_SESSION['role'] == Utilisateur::USER_ROLE_EMPLOYE) || ($_SESSION['role'] == Utilisateur::USER_ROLE_ADMIN)) {
        $ok = true;
        $commandes = $commandeDAO->loadAllCommande();
        $avis = $avisDAO->loadAvisAValider();
        $menus = $menuDAO->loadMenus();
    }
}

$data = $commandeDAO->loadChiffresMenus("", "", "");

// $dataNbCommande = [];
// $dataPrix = [];
$menu_options = [];

$labels = [];
$values = [];
$cavalues = [];

if ($_SESSION['role'] == Utilisateur::USER_ROLE_ADMIN) {
    foreach ($data as $key => $value) {
        // $inputNbCmd = array("label" => $key, "y" => $data[$key]['nbCommande']);
        // array_push($dataNbCommande, $inputNbCmd);
        // $inputPrix = array("label" => $key, "y" => $data[$key]['prix']);
        // array_push($dataPrix, $inputPrix);

        $labels[] = $key;
        $values[] = $data[$key]['nbCommande'];
        $cavalues[] = $data[$key]['prix'];
    }
    // print_r($labels);
    $menu_options = $commandeDAO->loadMenus();


    // $labels[] = "Pizza";
    // $values[] = 1;
    // $labels[] = "Burger";
    // $values[] = 2;
    // $labels[] = "Tacos";
    // $values[] = 3;
    // $labels[] = "Pâte";
    // $values[] = 4;
    // $labels[] = "Briyani";
    // $values[] = 5;
    // $labels[] = "Bonda";
    // $values[] = 6;

}
?>

<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <link rel="stylesheet" href="styles/accueil_employe.css">
    <title>Accueil</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            /* padding: 40px;
            display: flex; */
            flex-direction: column;
            align-items: center;
        }

        .chart-container {
            width: 100%;
            max-width: 700px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>

    <?php include 'header.php' ?>


    <!-- main -->
    <main>
        <?php if ($ok): ?>

            <?php if (isset($_SESSION["id"]) && isset($_SESSION["role"]) && $_SESSION["role"] == Utilisateur::USER_ROLE_ADMIN): ?>
                <div class="pick_list_container">

                    <!-- Filtre Nom Menu -->
                    <div class="menu_filter_section">
                        <label class="menu_filter" for="menu_filter">Menu</label>
                        <select name="menu_options[]" id="menu_filter">
                            <?php
                            echo '<option value="All" selected>-- All --</option>';
                            foreach ($menu_options as $e) {
                                echo '<option value="' . $e . '">' . $e . '</option>';
                            }
                            ?>

                        </select>
                    </div>

                    <div class="date_start_section">
                        <label class="start_filter" for="start_filter">Date début</label>
                        <input type="date" id="start_date" name="start_date" />
                    </div>

                    <div class="date_end_section">
                        <label class="end_filter" for="end_filter">Date fin</label>
                        <input type="date" id="end_date" name="end_date" />
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="menuChart"></canvas>

                    <canvas id="caChart"></canvas>
                </div>

                <script>
                    // 4. Passage des données de PHP à JavaScript en utilisant json_encode
                    const menusLabels = <?php echo json_encode($labels); ?>;
                    const commandesValues = <?php echo json_encode($values); ?>;
                    const cavalues = <?php echo json_encode($cavalues); ?>;

                    // 5. Configuration et initialisation de Chart.js
                    const ctx = document.getElementById('menuChart').getContext('2d');

                    // Créer graphique + paramètres
                    const menuChart = new Chart(ctx, {
                        type: 'bar', // Type de graphique : 'bar' (bâtons), 'pie' (camembert), 'doughnut', etc.
                        data: {
                            labels: menusLabels, // Les étiquettes (les noms des menus)
                            datasets: [{
                                label: 'Nombre de ventes',
                                data: commandesValues, // Les données numériques
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.2)',  // Bleu
                                    'rgba(16, 185, 129, 0.2)',  // Vert
                                    'rgba(245, 158, 11, 0.2)',  // Orange
                                    'rgba(239, 68, 68, 0.2)',   // Rouge
                                    'rgba(139, 92, 246, 0.2)'   // Violet
                                ],
                                borderColor: [
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(16, 185, 129, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(139, 92, 246, 1)'
                                ],
                                borderWidth: 1,
                                borderRadius: 6 // Arrondir le haut des barres
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false // Masque la légende globale car le titre suffit
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true, // Force l'axe Y à démarrer à 0
                                    ticks: {
                                        stepSize: 1 // Évite d'afficher des demi-commandes (ex: 1.5)
                                    }
                                }
                            }
                        }
                    });

                    // 5. Configuration et initialisation de Chart.js
                    const ctx2 = document.getElementById('caChart').getContext('2d');

                    // Créer graphique + paramètres
                    const caChart = new Chart(ctx2, {
                        type: 'bar', // Type de graphique : 'bar' (bâtons), 'pie' (camembert), 'doughnut', etc.
                        data: {
                            labels: menusLabels, // Les étiquettes (les noms des menus)
                            datasets: [{
                                label: 'Chiffre d\'affaire',
                                data: cavalues, // Les données numériques
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.2)',  // Bleu
                                    'rgba(16, 185, 129, 0.2)',  // Vert
                                    'rgba(245, 158, 11, 0.2)',  // Orange
                                    'rgba(239, 68, 68, 0.2)',   // Rouge
                                    'rgba(139, 92, 246, 0.2)'   // Violet
                                ],
                                borderColor: [
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(16, 185, 129, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(139, 92, 246, 1)'
                                ],
                                borderWidth: 1,
                                borderRadius: 6 // Arrondir le haut des barres
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false // Masque la légende globale car le titre suffit
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true, // Force l'axe Y à démarrer à 0
                                    ticks: {
                                        stepSize: 100 // Évite d'afficher des demi-commandes (ex: 1.5)
                                    }
                                }
                            }
                        }
                    });



                    function updateGraph(selectedMenu, selectedStartDate, selectedEndDate) {
                        // Envoi de la valeur au script PHP
                        fetch('update_graph_data.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'nouvelle_valeur=' + encodeURIComponent(selectedMenu) +
                                '&nouvelleStartDate=' + encodeURIComponent(selectedStartDate) +
                                '&nouvelleEndDate=' + encodeURIComponent(selectedEndDate)
                        })
                            .then(response => response.json()) // On attend du JSON en retour !
                            .then(data => {

                                // console.log("--- RÉPONSE BRUTE DU SERVEUR ---");
                                // console.log(texteBrut); // <-- C'est CETTE ligne qui va tout vous révéler
                                // console.log("--------------------------------");
                                // // 3. Mise à jour du graphique avec les données renvoyées par PHP

                                // // Exemple : On ajoute un nouveau label et la nouvelle valeur reçue
                                // const data = JSON.parse(texteBrut);
                                // console.log(data);
                                menuChart.data.labels = data.nouveauLabel;
                                menuChart.data.datasets[0].data = data.nouvelleValeur;
                                menuChart.update();

                                caChart.data.labels = data.nouveauLabel;
                                caChart.data.datasets[0].data = data.cavalues;
                                caChart.update();
                            })
                            .catch(error => console.error('Erreur:', error));
                    }


                    let selectedMenu = document.getElementById('menu_filter').value;
                    let selectedStartDate = document.getElementById('start_date').value;
                    let selectedEndDate = document.getElementById('end_date').value;


                    // Mettre à jour graphique quand le menu change
                    document.getElementById('menu_filter').addEventListener('change', function () {
                        selectedMenu = this.value;
                        if (selectedMenu === "") return;

                        // console.log('selectedMenu:' + selectedMenu);

                        updateGraph(selectedMenu, selectedStartDate, selectedEndDate);

                    });

                    // Mettre à jour graphique quand la start date change
                    document.getElementById('start_date').addEventListener('change', function () {
                        selectedStartDate = this.value;
                        if (selectedStartDate === "") return;

                        // console.log('selectedDate:' + selectedDate);

                        updateGraph(selectedMenu, selectedStartDate, selectedEndDate);
                    });

                    // Mettre à jour graphique quand la end date change
                    document.getElementById('end_date').addEventListener('change', function () {
                        selectedEndDate = this.value;
                        if (selectedEndDate === "") return;

                        // console.log('selectedDate:' + selectedDate);

                        updateGraph(selectedMenu, selectedStartDate, selectedEndDate);
                    });
                </script>


            <?php endif; ?>


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