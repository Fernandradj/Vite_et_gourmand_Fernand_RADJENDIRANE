<?php

class UtilisateurControleur
{
    private PDO $pdo;
    private Resultat $actionResult;

    public function handleRequest(PDO $pdo): void
    {
        $this->pdo = $pdo;
        $this->actionResult = new Resultat();

        if (isset($_POST['suspendre'])) {
            $this->suspendreUtilisateurs();
        }
    }

    public function suspendreUtilisateurs(): void
    {
        if (isset($_POST['users']) && !empty($_POST['users'])) {
            $userIds = $_POST['users'];
             $userDAO = new UtilisateurDAO($this->pdo);
            $result =  $userDAO->suspendreUtilisateurs($userIds);
            if ($result->getSucceeded()) {
                $this->actionResult->setSucceeded(true);
                $this->actionResult->setMessage($result->getMessage());
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
                $this->actionResult->setRedirect(true);
                $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/compte_utilisateurs.php');
            } else {
                $this->actionResult->setSucceeded(false);
                $this->actionResult->setMessage($result->getMessage());
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
            }
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage("Veuillez sélectionner au moins un utilisateur à suspendre.");
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function getResult(): Resultat
    {
        return $this->actionResult;
    }
}

?>