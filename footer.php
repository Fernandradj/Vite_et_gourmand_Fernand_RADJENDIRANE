<!-- footer -->
<footer>

    <div class="footer_section">
        <div class="footer_left">
            <div class="business-hours">
                <h2 class="title">Horaire d'ouvertures</h2>
                <ul class="list-unstyled opening-hours">
                    <?php
                    $horaireDAO = new HoraireDAO($pdo);
                    $horaire_list = $horaireDAO->loadHoraire();


                    if (!empty($horaire_list)) {
                        foreach ($horaire_list as $horaire) {
                            echo '<li>' . $horaire->getJour() . '<span class="pull-right">' . $horaire->getHoraire_ouverture() . '-' . $horaire->getHoraire_fermeture() . '</span></li>';

                        }
                    }

                    ?>

                    <!-- <li>Sunday <span class="pull-right">Closed</span></li> -->
                    <!-- <li>Monday <span class="pull-right">9:00-22:00</span></li> -->
                    <!-- <li>Tuesday <span class="pull-right">9:00-22:00</span></li>
                    <li>Wednesday <span class="pull-right">9:00-22:00</span></li>
                    <li>Thursday <span class="pull-right">9:00-22:00</span></li>
                    <li>Friday <span class="pull-right">9:00-23:30</span></li>
                    <li>Saturday <span class="pull-right">14:00-23:30</span></li> -->
                </ul>
            </div>

        </div>


        <div class="footer_right">


            <div>
                <a class="footer_mention" href="<?php echo BASE_URL_VUE."mention_legale.php"?>">Mentions Légales</a>

            </div>

            <div class="footer_condition_section">

                <a class="condition_text" href="<?php echo BASE_URL_VUE."conditions_generales.php"?>">Conditions Générales
                </a>
            </div>
        </div>



    </div>




</footer>

<!-- scripts bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>