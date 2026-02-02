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
            <p class="home_main_title">Ensemble sur la route, pour un avenir durable</p>
            <a class="btn_go" href="recherche_menu.php">C'est parti !!!</a>
        </div>

        <!-- description entreprise -->
        <section class="hero-section">
            <div class="">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="hero-title">Welcome to Our Website</h1>
                        <p class="hero-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam
                            commodo urna magna,
                            vel faucibus tellus mattis vitae.</p>
                    </div>
                    <div class="col-md-6">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw3fHxoZXJvfGVufDB8MHx8fDE3MTIwNzMwNDh8MA&ixlib=rb-4.0.3&q=80&w=1080"
                            alt="Hero Image" class="img-fluid hero-image">
                    </div>
                </div>
            </div>
        </section>


        <!-- description equipe -->
        <section class="hero-section hero-section-colour">
            <div class="">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w0NzEyNjZ8MHwxfHNlYXJjaHw3fHxoZXJvfGVufDB8MHx8fDE3MTIwNzMwNDh8MA&ixlib=rb-4.0.3&q=80&w=1080"
                            alt="Hero Image" class="img-fluid hero-image">
                    </div>
                    <div class="col-md-6">
                        <h1 class="hero-title">Welcome to Our Website</h1>
                        <p class="hero-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam
                            commodo urna magna,
                            vel faucibus tellus mattis vitae.</p>
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
                        <h2 class="section-title">Meet Our Team</h2>
                        <p class="text-muted">Dedicated professionals working together to achieve excellence</p>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="g-4 service_section">
                    <!-- Team Member 1 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member text-center p-4">
                            <img src="https://randomuser.me/api/portraits/women/64.jpg" alt="Team Member 1"
                                class="mb-4 shadow">
                            <h5 class="mb-1">Sarah Johnson</h5>
                            <p class="text-muted mb-3">CEO & Founder</p>
                            <p class="small mb-3">Leading our company's vision and strategy with over 15 years of
                                experience.</p>

                        </div>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member text-center p-4">
                            <img src="https://randomuser.me/api/portraits/men/64.jpg" alt="Team Member 2"
                                class="mb-4 shadow">
                            <h5 class="mb-1">Michael Chen</h5>
                            <p class="text-muted mb-3">Tech Lead</p>
                            <p class="small mb-3">Driving innovation and technical excellence in all our projects.</p>

                        </div>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member text-center p-4">
                            <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Team Member 3"
                                class="mb-4 shadow">
                            <h5 class="mb-1">Emily Martinez</h5>
                            <p class="text-muted mb-3">Design Director</p>
                            <p class="small mb-3">Creating beautiful and intuitive designs that users love.</p>

                        </div>
                    </div>


                </div>
            </div>
        </section>
        <!-- Review section -->
        <section class="testimonial py-5 bg-light">

            <h2 class="text-center mb-5">What Our Clients Say</h2>
            <div class="review_section">

                <?php

                $avis_list = Avis::loadBestAvis($pdo);
                // print_r($avis);
                
                if (!empty($avis_list)) {
                    foreach ($avis_list as $avis) {
                        echo "<div class='col-md-4 mb-4 review_item'>";
                        echo '<div class="card">';
                        echo '<div class="card-body text-center">';
                        echo '<img src="image.php?userId="' . $avis->getSoumisPar()->getId() . '" alt="Image depuis la BDD">';
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