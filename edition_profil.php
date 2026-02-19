<?php include 'imports.php' ?>
<?php include 'session.php' ?>
<?php

$succesdMsg = "";
$errorMsg = "";
$userIsChauffeur = false;
$userIsPassager = false;

if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
    $user = new Utilisateur(true, $_SESSION['id'], $pdo);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["saveProfile"])) {

        // Process form submission and update user data
        $lastName = htmlspecialchars($_POST['last_name']);
        $firstName = htmlspecialchars($_POST['first_name']);
        $address = htmlspecialchars($_POST['address']);
        $phone = htmlspecialchars($_POST['phone']);
        $email = htmlspecialchars($_POST['email']);
        $username = htmlspecialchars($_POST['username']);
        $dateOfBirth = htmlspecialchars($_POST['dob']);
        // $credit = floatval($_POST['credit']);
        
        // Check if a new password was provided and hash it
        $hashedPassword = "";
        if (!empty($_POST['password'])) {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (isset($_SESSION['id']) && isset($_SESSION['role'])) {
            // $currentRole = $_SESSION['role'];
            // if ($currentRole != $userRole) {
                
                // Delete current role(s)
                // $sql = "DELETE FROM utilisateur_role WHERE Utilisateur_Id = ?";
                // $stmt = $pdo->prepare($sql);
                // $stmt->execute([$_SESSION['id']]);

                // Add updated role(s)
                // $newRoleIds = Utilisateur::loadIdsFromRoles($userRole, $pdo);
                // foreach ($newRoleIds as $roleId) {
                //     $sql = "INSERT INTO utilisateur_role (Utilisateur_Id, Role_Id) VALUES (?, ?)";
                //     $stmt = $pdo->prepare($sql);
                //     $stmt->execute([$_SESSION['id'], $roleId]);
                // }
            // }
            
            $imgContent = "";
            if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
                $image = $_FILES['photo']['tmp_name'];
                $imgContent = file_get_contents($image);
            }

            $result = $user->updateUserProfile($username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $hashedPassword, $imgContent, $pdo);

            if ($result->getSucceeded()) {
                // $_SESSION['role'] = $userRole;
                $succesdMsg = $result->getMessage();
                header("Refresh:2");
            }
            else {
                // $userRole = $_SESSION['role'];
                $errorMsg = $result->getMessage();
                header("Refresh:2");
            }
        }
    }
}
?>

<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <title>Mon Profil</title>
</head>

<body id="body">

    <?php include 'header.php'?>

    <!-- main -->
    <main>

        <div class="profile-container">

            <?php if (!empty($succesdMsg)): ?>
            <div class="success-msg"><?php echo $succesdMsg; ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMsg)): ?>
            <div class="error-msg"><?php echo $errorMsg; ?></div>
            <?php endif; ?>
            
            <h2>Mon profil</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post"
                enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group photo-upload full-width">
                        <div class="photo-preview">
                            <img src="<?php echo "image.php?userId=".$_SESSION['id']?>" alt="Image depuis la BDD">
                        </div>
                        <label for="photo">Photo de profil</label>
                        <input type="file" id="photo" name="photo">
                    </div>

                    <div class="form-group">
                        <label for="last_name">Nom</label>
                        <input type="text" id="last_name" name="last_name"
                            value="<?php echo htmlspecialchars($user->getLastName()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input type="text" id="first_name" name="first_name"
                            value="<?php echo htmlspecialchars($user->getFirstName()); ?>" required>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address">Adresse</label>
                        <input type="text" id="address" name="address"
                            value="<?php echo htmlspecialchars($user->getAddress()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($user->getTelephone()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username"
                            value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date de naissance</label>
                        <input type="date" id="dob" name="dob"
                            value="<?php echo htmlspecialchars($user->getDateOfBirth()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password"
                            placeholder="Laissez vide pour ne pas changer">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="saveProfile">Enregistrer les
                            modifications</button>
                    </div>

                </div>
            </form>
        </div>
    </main>


    <?php include 'footer.php'?>

</body>

</html>