<?php

class AvisControleur
{
    private PDO $pdo;
    private Resultat $actionResult;

    public function handleRequest(int $commandeId, int $avisId, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->actionResult = new Resultat();

        if (isset($_POST["soumettre"])) {
            $this->soumettreAvis($commandeId);
        } else if (isset($_POST["valider"])) {
            $this->validerAvis($avisId);
        } else if (isset($_POST["rejeter"])) {
            $this->rejeterAvis($avisId);
        }
    }

    private function soumettreAvis(int $commandeId): void
    {
        if (isset($_POST['note']) && (!empty($_POST['commentaire'])) && ($_POST['commentaire'] != "")) {
            $note = $_POST['note'];
            $commentaire = $_POST['commentaire'];
            $utilisateurId = $_SESSION['id'];
            $avisDAO = new AvisDAO($this->pdo);
            $result = $avisDAO->soumettreAvis($commandeId, $note, $commentaire, $utilisateurId);
            if ($result->getSucceeded()) {
                $this->actionResult->setSucceeded(true);
                $this->actionResult->setMessage($result->getMessage());
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
                $this->actionResult->setRedirect(true);

                $avisId = $avisDAO->loadAvisIdOfCommande($commandeId);
                $this->actionResult->setRedirectURL(BASE_URL_VUE . 'detail_avis.php?avisId=' . $avisId);
            } else {
                $this->actionResult->setSucceeded(false);
                $this->actionResult->setMessage($result->getMessage());
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
            }
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage("Veuillez remplir tous les champs correctement.");
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function validerAvis(int $avisId): void
    {
        $utilisateurId = $_SESSION['id'];

        $avisDAO = new AvisDAO($this->pdo);
        $result = $avisDAO->validerAvis($avisId, $utilisateurId);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL(BASE_URL_VUE . 'detail_avis.php?avisId=' . $avisId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function rejeterAvis(int $avisId): void
    {
        $utilisateurId = $_SESSION['id'];
        $avisDAO = new AvisDAO($this->pdo);
        $result = $avisDAO->rejeterAvis($avisId, $utilisateurId);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL(BASE_URL_VUE . 'detail_avis.php?avisId=' . $avisId);
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