<?php
class Horaire
{
    private PDO $pdo;
    private int $horaire_id;
    private string $jour;
    private string $heure_ouverture;
    private string $heure_fermeture;

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

}

?>