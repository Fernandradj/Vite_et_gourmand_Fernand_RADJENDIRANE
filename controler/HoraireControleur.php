<?php

class HoraireControleur
{

    private PDO $pdo;

    private Resultat $actionResult;

    public function handleRequest(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->actionResult = new Resultat();

        if (isset($_POST["enregistrer"])) {
            $this->enregistrerHoraire();
        }
    }

    public function enregistrerHoraire(): void
    {
        $horaireDAO = new HoraireDAO($this->pdo);
        $result = $horaireDAO->saveHoraire($_POST);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL(BASE_URL_VUE . 'horaire.php');
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function getResult(): Resultat
    {
        return $this->actionResult;
    }
}


?>