<?php
class Horaire
{
    private PDO $pdo;
    private int $horaire_id;
    private string $jour;
    private string $heure_ouverture;
    private string $heure_fermeture;

    public const RESULT_HORAIRE_REJECTED = "Les horaires ont bien été enregistrés.";

    public const RESULT_FAIL = "Une erreur s'est produite lors de l'enregistrement. Veuilllez réessayer ultérieurement.";

    public function __construct(int $horaire_id, string $jour, string $heure_ouverture, string $heure_fermeture, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->horaire_id = $horaire_id;
        $this->jour = $jour;
        $this->heure_ouverture = $heure_ouverture;
        $this->heure_fermeture = $heure_fermeture;
    }

    public static function loadHoraire(PDO $pdo): array
    {
        $sql = "SELECT Horaire_Id, Jour, Heure_ouverture, Heure_fermeture FROM horaire";
        $stmt = $pdo->prepare(query: $sql);
        $stmt->execute(params: []);
        $resultat = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);
        $horaire = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_horaire = new Horaire($value["Horaire_Id"], $value["Jour"], $value["Heure_ouverture"], $value["Heure_fermeture"], $pdo);
                array_push($horaire, $new_horaire);
            }
        }
        return $horaire;
    }
    public function getHoraire_Id(): int
    {
        return $this->horaire_id;
    }
    public function getJour(): string
    {
        return $this->jour;
    }

    public function getOuverture(): string
    {
        return $this->heure_ouverture;
    }

    public function getFermeture(): string
    {
        return $this->heure_fermeture;
    }

    public function getHoraire_ouverture(): string
    {
        // return $this->heure_ouverture;
        $date = DateTime::createFromFormat('H:i:s', $this->heure_ouverture);
        return $date->format('H\hi');
    }

    public function getHoraire_fermeture(): string
    {
        $date = DateTime::createFromFormat('H:i:s', $this->heure_fermeture);
        return $date->format('H\hi');

    }

    public static function saveHoraire(array $inputs, PDO $pdo): Resultat
    {
        $sql = "UPDATE horaire 
            SET Heure_ouverture = :ouverture, Heure_fermeture = :fermeture 
            WHERE Horaire_Id = :id";
        $stmt = $pdo->prepare($sql);

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