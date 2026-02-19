<?php include 'imports.php' ?>
<?php include 'session.php' ?>

<?php

$resultats = [];
if (isset($_SESSION['id']) && isset($_SESSION['role']) && ($_SESSION['role'] == Utilisateur::USER_ROLE_ADMIN)) {
    $resultats = Utilisateur::loadAllUsers($pdo);
}

require_once($currentFolder . "/controler/UtilisateurControleur.php");
$controller = new UtilisateurControleur();
$controller->handleRequest($pdo);

?>

<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <link rel="stylesheet" href="styles/compte_utilisateurs.css">
    <title>Eco Ride</title>
</head>

<body>

    <?php include 'header.php' ?>
    <!-- main -->
    <main>

        <!-- <a href="creation_compte.php">Créer un nouvel employé</a> -->

        <div class="liste_voyages_container">
            <div id="messages">
                <?php
                $statusMessage = $controller->getResult();
                include 'message.php';

                if ($statusMessage->getRedirect()) {
                    header('Refresh: 2; url=' . $statusMessage->getRedirectURL());
                    exit();
                }
                ?>
            </div>
            <div class="employe_header">
                <h1>Listes des utilisateurs</h1>
                <a class="btn btn-primary" href="creer_menu.php" role="button">+ Créer un employé</a>
            </div>
            <form method="post">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Role(s)</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($resultats)) {
                            foreach ($resultats as $user) {
                                echo "<tr>";
                                if ($user->isActif()) {
                                    echo "<td><input type='checkbox' name='users[]' value='" . htmlspecialchars($user->getId()) . "'></td>";
                                } else {
                                    echo "<td></td>";
                                }
                                echo "<td>" . htmlspecialchars($user->getLastName()) . "</td>";
                                echo "<td>" . htmlspecialchars($user->getFirstName()) . "</td>";
                                echo "<td>" . htmlspecialchars($user->getUsername()) . "</td>";
                                echo "<td>" . htmlspecialchars($user->getEmail()) . "</td>";
                                echo "<td>" . htmlspecialchars($user->getTelephone()) . "</td>";
                                echo "<td>" . htmlspecialchars($user->getUserRole()) . "</td>";
                                echo "<td>" . htmlspecialchars($user->getStatut()) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>Aucun utilisateur trouvé.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <div class="btn_group">
                    <button class="action-btn" type="submit" name="suspendre">Suspendre</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php' ?>

</body>

</html>