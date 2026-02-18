<?php

use LDAP\Result;

class CommandeControleur
{

    private PDO $pdo;
    private Resultat $actionResult;

    private string $selectedAddress;
    private string $selectedDate;

    public function handleRequest(int $menu_Id, int $commande_id, PDO $pdo): void
    {
        $this->pdo = $pdo;
        $this->actionResult = new Resultat();
        $this->selectedAddress = "";
        $this->selectedDate = "";

        if (isset($_POST['commander'])) {
            $this->saveCommande($menu_Id);
        } else if (isset($_POST['annuler'])) {
            $this->annulerCommande($commande_id);
        } else if (isset($_POST['modifier'])) {
            $this->modifierCommande($commande_id);
        }
        else if (isset($_POST['donnerAvis'])) {
            header("Location: http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/detail_avis.php?commandeId=" . $commande_id);
        }
         else if (isset($_POST['valider'])) {
            $this->validerCommande($commande_id);
        }
         else if (isset($_POST['preparer'])) {
            $this->preparerCommande($commande_id);
        }
         else if (isset($_POST['expedier'])) {
            $this->expedierCommande($commande_id);
        }
         else if (isset($_POST['livrer'])) {
            $this->livrerCommande($commande_id);
        }
         else if (isset($_POST['terminer'])) {
            $this->terminerCommande($commande_id);
        }
    }

    private function saveCommande(int $menu_Id): void
    {
        $nombre_pers = htmlspecialchars($_POST['nb_personne']);
        $date_cmd = date("Y-m-d");
        $date_heure_liv = htmlspecialchars($_POST['date_heure_livraison']);
        $totale_cmd = htmlspecialchars($_POST["totale_commande"]);
        $prix_liv = htmlspecialchars($_POST["totale_livraison"]);
        $prix_distance = htmlspecialchars($_POST["distance_livraison"]);
        $reduction = htmlspecialchars($_POST["reduction"]);
        $prix_totale = htmlspecialchars($_POST["prix_totale"]);
        $statut = Commande::COMMANDE_STATUT_COMMANDE;
        $utilisateur_id = $_SESSION["id"];
        $menu_id = $menu_Id;
        $entree_id = htmlspecialchars($_POST["entree"]);
        $plat_id = htmlspecialchars($_POST["plat"]);
        $dessert_id = htmlspecialchars($_POST["dessert"]);
        $addresse_livraison = htmlspecialchars($_POST["adresse"]);
        $quantite_restante = htmlspecialchars(string: $_POST["quantite_restante"]);

        $this->selectedAddress = $addresse_livraison;
        $this->selectedDate = $date_heure_liv;

        if (!empty($addresse_livraison) && !empty($date_heure_liv)) {
            if ($quantite_restante > 0) {
                // echo "ok";
                $result = Commande::saveCommande($nombre_pers, $date_cmd, $date_heure_liv, $totale_cmd, $prix_liv, $prix_distance, $reduction, $prix_totale, $statut, $utilisateur_id, $menu_id, $entree_id, $plat_id, $dessert_id, $addresse_livraison, $this->pdo);

                if ($result->getSucceeded()) {
                    $this->actionResult->setSucceeded(true);
                    $this->actionResult->setMessage($result->getMessage());
                    $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
                    $this->actionResult->setRedirect(true);

                    $numero_commande = Commande::loadLastCommandeOfUser($utilisateur_id, $this->pdo);

                    $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $numero_commande);
                } else {
                    $this->actionResult->setSucceeded(false);
                    $this->actionResult->setMessage($result->getMessage());
                    $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
                }
            } else {
                $this->actionResult->setSucceeded(false);
                $this->actionResult->setMessage("Ce menu sera bientôt disponible.");
                $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);

                $this->actionResult->setRedirect(true);
                $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/menus.php');
            }
        } else {
            // display message
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage("Veuillez remplir tous les champs correctement.");
            $this->actionResult->setDisplay_type(newValue: Resultat::DISPLAY_TYPE_ERREUR);
        }

        // $statusMesasge = $this->actionResult;
    }

    public function annulerCommande(int $commande_id): void
    {
        // echo "annuler";
        $commandeId = $commande_id;
        $result = Commande::annulerCommande($commandeId, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function modifierCommande(int $commande_id): void
    {
        // echo "modifier";
        $commandeId = $commande_id;
        $date_heure_liv = htmlspecialchars($_POST['date_heure_livraison']);
        $addresse_livraison = htmlspecialchars($_POST['adresse']);
        $plat_id = htmlspecialchars($_POST["plat"]);
        $dessert_id = htmlspecialchars($_POST["dessert"]);
        echo $dessert_id;
        $entree_id = htmlspecialchars($_POST["entree"]);
        $nombrePersonne = htmlspecialchars($_POST["nb_personne"]);
        $totale_cmd = htmlspecialchars($_POST["totale_commande"]);
        $prix_liv = htmlspecialchars($_POST["totale_livraison"]);
        $prix_distance = htmlspecialchars($_POST["distance_livraison"]);
        $reduction = htmlspecialchars($_POST["reduction"]);
        $prix_totale = htmlspecialchars($_POST["prix_totale"]);
        $pret_materiel = (isset($_POST['pret_materiel'])) ? 1 : 0;
        $restitution_materiel = (isset($_POST['restitution_materiel'])) ? 1 : 0;
        $result = Commande::modifierCommande($commandeId, $addresse_livraison,  $date_heure_liv, $plat_id, $dessert_id, $entree_id, $nombrePersonne, $pret_materiel, $restitution_materiel, $totale_cmd, $prix_liv, $prix_distance, $reduction, $prix_totale, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function validerCommande(int $commande_id): void
    {
        // echo "valider";
        $commandeId = $commande_id;
        $result = Commande::validerCommande($commandeId, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function preparerCommande(int $commande_id): void
    {
        // echo "preparer";
        $commandeId = $commande_id;
        $result = Commande::preparerCommande($commandeId, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }
    public function expedierCommande(int $commande_id): void
    {
        // echo "expedier";
        $commandeId = $commande_id;
        $result = Commande::expedierCommande($commandeId, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function livrerCommande(int $commande_id): void
    {
        // echo "livrer";   
        $commandeId = $commande_id;
        $pret_materiel = isset($_POST['pret_materiel2']) ? 1 : 0;
        $result = Commande::livrerCommande($commandeId, $pret_materiel, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
        } else {
            $this->actionResult->setSucceeded(false);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_ERREUR);
        }
    }

    public function terminerCommande(int $commande_id): void
    {
        $commandeId = $commande_id;
        $pret_materiel = isset($_POST['pret_materiel2']) ? 1 : 0;
        $result = Commande::terminerCommande($commandeId, $pret_materiel, $this->pdo);
        if ($result->getSucceeded()) {
            $this->actionResult->setSucceeded(true);
            $this->actionResult->setMessage($result->getMessage());
            $this->actionResult->setDisplay_type(Resultat::DISPLAY_TYPE_POPUP);
            $this->actionResult->setRedirect(true);
            $this->actionResult->setRedirectURL('http://localhost:3000/Vite_et_gourmand_Fernand_RADJENDIRANE/commande.php?commandeId=' . $commandeId);
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

    public function getSelectedAddress(): string
    {
        return $this->selectedAddress;
    }

    public function getSelectedDate(): string
    {
        return $this->selectedDate;
    }
}