<?php
ob_start();
require_once '../config.php';
?>
<?php include ROOT_PATH . 'imports.php' ?>
<?php include ROOT_PATH . 'session.php' ?>

<?php include ROOT_PATH . 'html.php' ?>

<head>
    <?php include ROOT_PATH . 'head.php' ?>
    <link rel="stylesheet" href="<?php echo BASE_URL_STYLE . "horaire.css" ?>" type="text/css">
    <title>Horaire</title>
</head>

<?php
$horaireDAO = new HoraireDAO($pdo);
$horaires = $horaireDAO->loadHoraire();

// require_once($currentFolder . "../controler/HoraireControleur.php");
$controller = new HoraireControleur();
$controller->handleRequest($pdo);

?>

<body>

    <?php include ROOT_PATH . 'header.php' ?>


    <!-- main -->
    <main>

        <div id="messages">
            <?php
            $statusMessage = $controller->getResult();
            include 'message.php';

            if ($statusMessage->getRedirect()) {
                $redirectUrl = $statusMessage->getRedirectURL();
                // echo 'goto ' . $redirectUrl;
                header('Refresh: 2; url=' . $redirectUrl);
                ?>
                <script>
                    setTimeout(function () {
                        window.location.href = "<?= $redirectUrl ?>";
                    }, 2000);
                </script>
                <?php
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

    <?php include ROOT_PATH . 'footer.php' ?>

</body>

</html>