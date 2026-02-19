<!-- header -->
<?php

$homepage = "index.php";
if (isset($_SESSION["id"]) && isset($_SESSION["role"])) {
    $role = $_SESSION["role"];

    if ($role == Utilisateur::USER_ROLE_ADMIN) {
        $homepage = "accueil_employe.php";
    } else if ($role == Utilisateur::USER_ROLE_EMPLOYE) {
        $homepage = "accueil_employe.php";
    } else if ($role == Utilisateur::USER_ROLE_UITILISATEUR) {
        $homepage = "accueil_utilisateur.php";
    }
}

?>
<header>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $homepage; ?>">
                <img src="images/Logo_vite_et_gourmand.png" class="vite_et_gourmand_logo" alt="Vite et Gourmand">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 navbar_list">
                    <li class="nav-item">
                        <a class="nav-link nav-item-link active" aria-current="page"
                            href="<?php echo $homepage; ?>">Accueil</a>
                    </li>
                    <?php if (isset($_SESSION["id"]) && isset($_SESSION["role"]) && $_SESSION["role"] == Utilisateur::USER_ROLE_UITILISATEUR): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link" href="menus.php">Menus</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION["id"]) && isset($_SESSION["role"]) && $_SESSION["role"] == Utilisateur::USER_ROLE_ADMIN): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link" href="compte_utilisateurs.php">Comptes</a>
                        </li>
                    <?php endif; ?>

                    
                    <?php if (isset($_SESSION["id"]) && isset($_SESSION["role"]) && $_SESSION["role"] != Utilisateur::USER_ROLE_UITILISATEUR): ?>
                        <li class="nav-item" hidden>
                            <a class="nav-link nav-item-link" href="horaires.php">Horaires</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link nav-item-link" href="contact.php">Contact</a>
                    </li>

                    <?php if (isset($_SESSION["id"]) && isset($_SESSION["username"])): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link"
                                href="edition_profil.php">Bonjour<?php echo " " . $_SESSION["username"] . " ! "; ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <?php
                        if (isset($_SESSION["id"])) {
                            echo "<a class=\"nav-link nav-item-link\" href=\"deconnexion.php\">";
                            echo "Se Déconnecter";
                        } else {
                            echo "<a class=\"nav-link nav-item-link\" href=\"connexion.php\">";
                            echo "Se Connecter";
                        }
                        echo "</a>";
                        ?>
                    </li>

                </ul>

            </div>
        </div>
    </nav>
</header>