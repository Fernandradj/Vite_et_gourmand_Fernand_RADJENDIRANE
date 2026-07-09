<?php include 'imports.php' ?>
<?php include 'session.php' ?>

<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <link rel="stylesheet" href="./styles/horaire.css" type="text/css">
    <title></title>
</head>

<?php
$horaireDAO = new HoraireDAO($pdo);
$horaires = $horaireDAO->loadHoraire();

require_once($currentFolder . "/controler/HoraireControleur.php");
$controller = new HoraireControleur();
$controller->handleRequest($pdo);

?>

<body>

    <?php include 'header.php' ?>


    <!-- main -->
    <main>

        <div id="messages">
            <?php
            $statusMessage = $controller->getResult();
            include 'message.php';

            if ($statusMessage->getRedirect()) {
                // echo 'goto ' . $statusMessage->getRedirectURL();
                header('Refresh: 2; url=' . $statusMessage->getRedirectURL());
                exit();
            }
            ?>
        </div>

        <div class="container horaire_container">
            <h2>🕒 Gestion des horaires</h2>
            <form method="POST">
                <?php foreach ($horaires as $h): ?>
                    <div class="row">
                        <strong>
                            <?= htmlspecialchars($h->getJour()) ?>
                        </strong>
                        <input type="hidden" name="id[]" value="<?= $h->getHoraire_Id() ?>">
                        <input type="time" name="ouverture[]" value="<?= $h->getOuverture() ?>" required>
                        à
                        <input type="time" name="fermeture[]" value="<?= $h->getFermeture() ?>" required>
                    </div>
                <?php endforeach; ?>
                <hr>
                <button type="submit" name="enregistrer">Enregistrer</button>
            </form>
        </div>

    </main>

    <?php include 'footer.php' ?>

</body>

</html>