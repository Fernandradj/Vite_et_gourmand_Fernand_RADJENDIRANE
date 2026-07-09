<?php
class SuiviDAO
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById(int $id, int $numero_commande = 0, string $statut = "", string $date = "", bool $done = false)
    {
        $suiviData = new Suivi();
        $suiviData->setId($id);

        $suiviData->setNumeroCommande($numero_commande);
        $suiviData->setStatut($statut);
        $suiviData->setDate($date);
        $suiviData->setDone(false);
        if ($id != null) {
            $suiviData->setDone(true);
        }
        return $suiviData;
    }

    public function loadSuivisByCommandeId(int $commande_id): array
    {
        $sql = "SELECT Suivi_Id, Numero_commande, Statut, Date FROM suivi WHERE Numero_commande = ? ORDER BY Date ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$commande_id]);
        $suivi_list = $stmt->fetchAll();
        $suivis = [];
        if ($suivi_list) {
            foreach ($suivi_list as $value) {
                $new_suivi = $this->getById($value["Suivi_Id"], $value["Numero_commande"], $value["Statut"], $value["Date"], true);
                array_push($suivis, $new_suivi);
            }
        }
        return $suivis;
    }

    public function loadFullSuivi(Commande $commande): array
    {
        $suivis = $commande->getSuivis();

        $fullSuivis = [];
        array_push($fullSuivis, Commande::COMMANDE_STATUT_COMMANDE);
        if ($commande->isAnnule()) {
            array_push($fullSuivis, Commande::COMMANDE_STATUS_ANNULE);
        } else {
            array_push($fullSuivis, Commande::COMMANDE_STATUS_VALIDE);
            array_push($fullSuivis, Commande::COMMANDE_STATUS_PREPARATION);
            array_push($fullSuivis, Commande::COMMANDE_STATUS_EXPEDIE);
            if ($commande->getPret_materiel()) {
                array_push($fullSuivis, Commande::COMMANDE_STATUS_ATTENTE_RETOUR);
            }
            array_push($fullSuivis, Commande::COMMANDE_STATUS_TERMINE);
        }

        $finalSuivis = [];
        for ($index = 0; $index < count($suivis); $index++) {
            array_push($finalSuivis, $suivis[$index]);
        }
        for ($index = count($suivis); $index < count($fullSuivis); $index++) {
            array_push($finalSuivis, $this->getById(0, 0, $fullSuivis[$index], "", false));
        }
        return $finalSuivis;
    }
}
?>