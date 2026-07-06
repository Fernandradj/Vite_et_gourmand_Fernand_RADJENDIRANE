<?php include 'imports.php' ?>
<?php include 'session.php' ?>

<?php include 'html.php' ?>

<head>
    <?php include 'head.php' ?>
    <title>Vite et Gourmand</title>
</head>

<body>

    <?php include 'header.php' ?>

    <!-- main -->
    <main>
        <div class="home_main_section">
            <p class="home_main_title">Bien manger n'a jamais été aussi rapide.</p>
            <a class="btn_go" href="menus.php">C'est parti !!!</a>
        </div>

        <!-- description entreprise -->
        <section class="hero-section">
            <div class="">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="hero-title">L'Équilibre : La Haute Cuisine à la Vitesse de l'Éclair</h1>
                        <p class="hero-description">Pourquoi choisir entre un repas d'exception et un emploi du temps
                            chargé ? Chez Vite et Gourmand, nous avons supprimé le compromis. Notre carte propose une
                            cuisine créative, élaborée à partir de produits frais et locaux, conçue spécifiquement pour
                            voyager sans perdre sa texture ni ses saveurs. Que vous soyez au bureau ou dans votre salon,
                            nous transformons votre pause repas en une véritable escale gastronomique, le tout en un
                            temps record.</p>
                    </div>
                    <div class="col-md-6">
                        <img src="images/cuisine.png" alt="Hero Image" class="img-fluid hero-image">
                    </div>
                </div>
            </div>
        </section>


        <!-- description equipe -->
        <section class="hero-section hero-section-colour">
            <div class="">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="images/equipe.png" alt="Hero Image" class="img-fluid hero-image">
                    </div>
                    <div class="col-md-6">
                        <h1 class="hero-title">L'Équipe : Les Artisans du Goût et du Mouvement</h1>
                        <p class="hero-description">À la tête de la cuisine, notre chef cuisinier voit chaque plat
                            comme un défi technique. Sa mission ? Créer des recettes audacieuses qui
                            conservent toute leur âme, de la première découpe en cuisine jusqu’à l'ouverture de votre
                            boîte de livraison.
                        </p>
                        <p class="hero-description">
                            Nos livreurs ne sont pas de simples coursiers ; ils sont le dernier maillon de notre chaîne
                            d'excellence. Leur priorité est double : veiller à ce que votre
                            commande arrive chaude (ou parfaitement fraîche) et vous l'apporter avec un sourire qui
                            prouve que l'efficacité peut aussi être chaleureuse.
                        </p>
                    </div>

                </div>
            </div>
        </section>
        <!-- description servicve-->
        <section class="py-5">
            <div>
                <!-- Section Header -->
                <div class="service_title_section text-center mb-5">
                    <div class="col-12">
                        <h2 class="section-title">Découvrez notre équipe</h2>
                        <p class="text-muted">Des professionnels dévoués travaillant ensemble pour atteindre
                            l'excellence</p>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="g-4 service_section">
                    <!-- Team Member 1 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member text-center p-4">
                            <img src="images/cuisine_logo.png" alt="Team Member 1" class="mb-4 shadow">
                            <h5 class="mb-1">Cuisine</h5>
                            <!-- <p class="text-muted mb-3">CEO & Founder</p> -->
                            <p class="small mb-3">Une brigade passionnée qui transforme des produits frais en créations
                                gourmandes, alliant rigueur technique et saveurs authentiques.</p>

                        </div>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member text-center p-4">
                            <img src="images/livraison_logo.png" alt="Team Member 2" class="mb-4 shadow">
                            <h5 class="mb-1">Livraison</h5>
                            <!-- <p class="text-muted mb-3">Tech Lead</p> -->
                            <p class="small mb-3">Un service logistique ultra-réactif garantissant l'arrivée de vos
                                plats à température idéale et dans un délai record.</p>

                        </div>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member text-center p-4">
                            <img src="images/sav logo.png" alt="Team Member 3" class="mb-4 shadow">
                            <h5 class="mb-1">SAV</h5>
                            <!-- <p class="text-muted mb-3">Design Director</p> -->
                            <p class="small mb-3">Une équipe attentive et disponible, dédiée à votre satisfaction pour
                                que chaque expérience soit aussi fluide que savoureuse.</p>

                        </div>
                    </div>


                </div>
            </div>
        </section>
        <!-- Review section -->
        <section class="testimonial py-5 bg-light">

            <h2 class="text-center mb-5">
                Ce que disent nos clients</h2>
            <div class="review_section">

                <?php

                $avis_list = $avisDAO->loadBestAvis();
                // print_r($avis);
                
                if (!empty($avis_list)) {
                    foreach ($avis_list as $avis) {
                        echo "<div class='col-md-4 mb-4 review_item'>";
                        echo '<div class="card">';
                        echo '<div class="card-body text-center">';
                        echo '<img class="avis_image" src="image.php?userId=' . $avis->getSoumisPar()->getId() . '" alt="Image depuis la BDD">';
                        echo '<h5 class="card-title">' . $avis->getSoumisPar()->getFullName() . '</h5>';
                        echo '<p class="card-text text-muted">' . $avis->getCommande()->getMenu()->getNom() . '</p>';
                        echo '<p class="card-text">' . $avis->getCommentaire() . '</p>';
                        // echo '<p class="card-text">' . $avis->getNote() . '</p>';
                        echo '<div class="text-warning">';
                        for ($x = 1; $x <= 5; $x++) {
                            if ($x <= $avis->getNote()) {
                                echo '<i class="bi bi-star-fill"></i>';
                            } else {
                                echo '<i class="bi bi-star"></i>';
                            }

                        }
                        // echo '<i class="bi bi-star-fill"></i>';
                        // echo '<i class="bi bi-star-fill"></i>';
                        // echo '<i class="bi bi-star-fill"></i>';
                        // echo '<i class="bi bi-star-fill"></i>';
                        // echo '<i class="bi bi-star-half"></i>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }

                ?>



            </div>

        </section>




    </main>

    <?php include 'footer.php' ?>

</body>

</html>