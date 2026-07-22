<?php require_once '../config.php'; ?>
<?php include ROOT_PATH . 'imports.php' ?>
<?php include ROOT_PATH . 'session.php' ?>

<?php

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Veuillez entrer une adresse email valide.";
    } else {
        // 1. Vérifier si l'email existe dans la base de données
        $sql = "SELECT Utilisateur_Id FROM utilisateur WHERE Email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 2. Générer un token unique
            $token = bin2hex(random_bytes(32)); // Génère un jeton aléatoire de 64 caractères hexadécimaux
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // Le jeton expire dans 1 heure
            $user_id = $user['Utilisateur_Id'];

            // 3. Insérer le token dans une table de réinitialisation
            // Créez une table appelée 'password_resets' avec les colonnes :
            // (Id, Utilisateur_Id, Token, Date_Expiration	)
            $sql_insert = "INSERT INTO nouveau_mot_de_passe (Utilisateur_Id, Token, Date_Expiration) VALUES (?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$user_id, $token, $expires]);

            // 4. Construire le lien de réinitialisation
            $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . "/Vite_et_gourmand_Fernand_RADJENDIRANE/creation_mot_de_passe.php?token=" . $token;
            // echo $reset_link;

            // 5. Simuler l'envoi de l'email (remplacez par une vraie fonction d'envoi d'email)
            $sujet = "Réinitialisation de votre mot de passe";
            $corps_email = "Bonjour,\n\n"
                . "Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :\n"
                . $reset_link . "\n\n"
                . "Ce lien expirera dans 1 heure.\n"
                . "Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet email.";

            mail($email, $sujet, $corps_email); // Décommentez pour un vrai envoi d'email
            $message = "Un lien de réinitialisation a été envoyé à votre adresse email. Veuillez vérifier votre boîte de
        réception.";

            // Pour le développement, affichons le lien
            echo "<div
            style='margin-top:20px;padding:10px;background-color:#ffeeba;border:1px solid #ffc107;border-radius:5px;'>
            Pour le test, le lien est : <a href='$reset_link'>$reset_link</a></div>";

        } else {
            $message = "Cette adresse email n'est pas enregistrée.";
        }
    }
}
?>


<?php include ROOT_PATH . 'html.php' ?>

<head>
    <?php include ROOT_PATH . 'head.php' ?>
    <title>Mot de passe oublié</title>
</head>

<body>

    <?php include ROOT_PATH . 'header.php' ?>


    <!-- main -->
    <main>
        <div class="container">
            <h1>🔑 Réinitialiser le mot de passe</h1>

            <?php if ($message): ?>
                <div class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <p>Veuillez entrer votre adresse email pour recevoir un lien de réinitialisation.</p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label for="email">Adresse Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Envoyer le lien de réinitialisation</button>
            </form>
        </div>

    </main>

    <?php include ROOT_PATH . 'footer.php' ?>

</body>

</html>