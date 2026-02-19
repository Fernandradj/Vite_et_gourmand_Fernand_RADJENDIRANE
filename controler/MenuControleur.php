<?php

class MenuControleur
{
    private PDO $pdo;
    private Resultat $actionResult;

    public function handleRequest(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->actionResult = new Resultat();

        if (isset($_POST["enregistrer"])) {
            $this->saveMenu();
        }
        else if (isset($_POST["creer"])) {
            $this->creerMenu();
        }
    }

    private function saveMenu(): void
    {
        if (
            isset($_POST['nom']) && ($_POST['nom'] != "")
            && isset($_POST['nb_personne']) && is_numeric($_POST['nb_personne'])
            && isset($_POST['prix_personne']) && is_numeric($_POST['prix_personne'])
            && isset($_POST['regime'])
            && isset($_POST['theme'])
            && isset($_POST['description']) && ($_POST['description'] != "")
            && isset($_POST['stock']) && is_numeric($_POST['stock'])
            && isset($_POST['entree']) && !empty($_POST['entree'])
            && isset($_POST['plat']) && !empty($_POST['plat'])
            && isset($_POST['dessert']) && !empty($_POST['dessert'])
        ) {
            $menuId = $_GET['menuId'];
            $nom = $_POST['nom'];
            $nombre_personne_minimum = $_POST['nb_personne'];
            $prix_par_personne = $_POST['prix_personne'];
            $regime = $_POST['regime'];
            $theme = $_POST['theme'];
            $description = $_POST['description'];
            $quantite_restante = $_POST['stock'];
            $condition = $_POST['condition'];
            $entreeIds = $_POST['entree'];
            $platIds = $_POST['plat'];
            $dessertIds = $_POST['dessert'];

            $result = Menu::saveMenu($menuId, $nom, $nombre_personne_minimum, $prix_par_personne, $regime, $theme, $description, $quantite_restante, $condition, $entreeIds, $platIds, $dessertIds, $this->pdo);
            if ($result->getSucceeded()) {
                $this->actionResult->setSucceeded(true);
                $this->actionResult->setMessage($result->getMessage());
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
                $this->actionResult->setRedirect(true);
                $menuId = Menu::loadMenuIdByName($nom, $this->pdo);
                $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/editer_menu.php?menuId=' . $menuId);
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

    private function creerMenu(): void
    {
        if (
            isset($_POST['nom']) && ($_POST['nom'] != "")
            && isset($_POST['nb_personne']) && is_numeric($_POST['nb_personne'])
            && isset($_POST['prix_personne']) && is_numeric($_POST['prix_personne'])
            && isset($_POST['regime'])
            && isset($_POST['theme'])
            && isset($_POST['description']) && ($_POST['description'] != "")
            && isset($_POST['stock']) && is_numeric($_POST['stock'])
            && isset($_POST['entree']) && !empty($_POST['entree'])
            && isset($_POST['plat']) && !empty($_POST['plat'])
            && isset($_POST['dessert']) && !empty($_POST['dessert'])
        ) {
            $nom = $_POST['nom'];
            $nombre_personne_minimum = $_POST['nb_personne'];
            $prix_par_personne = $_POST['prix_personne'];
            $regime = $_POST['regime'];
            $theme = $_POST['theme'];
            $description = $_POST['description'];
            $quantite_restante = $_POST['stock'];
            $condition = $_POST['condition'];
            $entreeIds = $_POST['entree'];
            $platIds = $_POST['plat'];
            $dessertIds = $_POST['dessert'];

            $result = Menu::creerMenu($nom, $nombre_personne_minimum, $prix_par_personne, $regime, $theme, $description, $quantite_restante, $condition, $entreeIds, $platIds, $dessertIds, $this->pdo);
            if ($result->getSucceeded()) {
                $this->actionResult->setSucceeded(true);
                $this->actionResult->setMessage($result->getMessage());
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
                $this->actionResult->setRedirect(true);
                $menuId = Menu::loadLastMenuCreated($this->pdo);
                $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/editer_menu.php?menuId=' . $menuId);
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
    public function getResult(): Resultat
    {
        return $this->actionResult;
    }

}

?>