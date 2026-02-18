<?php
class Suivi
{
    private int $id;
    private int $numero_commande;
    private string $statut;
    private string $date;

    private bool $done;

    public function __construct(int $id, int $numero_commande = 0, string $statut = "", string $date = "", bool $done = false)
    {
        $this->id = $id;
        $this->numero_commande = $numero_commande;
        $this->statut = $statut;
        $this->date = $date;
        $this->done = false;
        if ($id != null) {
            $this->done = true;
        }
    }

    public static function loadSuivisByCommandeId(int $commande_id, PDO $pdo): array
    {
        $sql = "SELECT Suivi_Id, Numero_commande, Statut, Date FROM suivi WHERE Numero_commande = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$commande_id]);
        $suivi_list = $stmt->fetchAll();
        $suivis = [];
        if ($suivi_list) {
            foreach ($suivi_list as $value) {
                $new_suivi = new Suivi($value["Suivi_Id"], $value["Numero_commande"], $value["Statut"], $value["Date"], true);
                array_push($suivis, $new_suivi);
            }
        }
        return $suivis;
    }

    public static function loadFullSuivi(Commande $commande): array
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
            array_push($finalSuivis, new Suivi(0, 0, $fullSuivis[$index], "", false));
        }
        return $finalSuivis;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumeroCommande(): string
    {
        return $this->numero_commande;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDate(): string
    {
        return substr($this->date, 0, 16);
    }

    public function getDone(): bool
    {
        return $this->done;
    }
}

?>