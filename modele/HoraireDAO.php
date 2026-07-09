<?php
class HoraireDAO
{



    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById(int $horaire_id, string $jour, string $heure_ouverture, string $heure_fermeture)
    {

        $horaireData = new Horaire();
        $horaireData->setHoraire_Id($horaire_id);
        $horaireData->setJour($jour);
        $horaireData->setOuverture($heure_ouverture);
        $horaireData->setFermeture($heure_fermeture);
        return $horaireData;
    }

    public function loadHoraire(): array
    {
        $sql = "SELECT Horaire_Id, Jour, Heure_ouverture, Heure_fermeture FROM horaire";
        $stmt = $this->pdo->prepare(query: $sql);
        $stmt->execute(params: []);
        $resultat = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
        $horaire = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_horaire = $this->getById($value["Horaire_Id"], $value["Jour"], $value["Heure_ouverture"], $value["Heure_fermeture"]);
                array_push($horaire, $new_horaire);
            }
        }
        return $horaire;
    }

    public function saveHoraire(array $inputs): Resultat
    {
        $sql = "UPDATE horaire 
            SET Heure_ouverture = :ouverture, Heure_fermeture = :fermeture 
            WHERE Horaire_Id = :id";
        $stmt = $this->pdo->prepare($sql);

        try {
            // On boucle sur les IDs envoyés
            foreach ($inputs['id'] as $key => $id) {
                $stmt->execute([
                    'ouverture' => $inputs['ouverture'][$key],
                    'fermeture' => $inputs['fermeture'][$key],
                    'id' => $id
                ]);
            }
            return new Resultat(true, Horaire::RESULT_HORAIRE_REJECTED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }


}

?>