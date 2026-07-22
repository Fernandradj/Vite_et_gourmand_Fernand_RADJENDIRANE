<?php
class Horaire
{
    private int $horaire_id;
    private string $jour;
    private string $heure_ouverture;
    private string $heure_fermeture;

    public const RESULT_HORAIRE_REJECTED = "Les horaires ont bien été enregistrés.";

    public const RESULT_FAIL = "Une erreur s'est produite lors de l'enregistrement. Veuilllez réessayer ultérieurement.";


    public function getHoraire_Id(): int
    {
        return $this->horaire_id;
    }
    public function setHoraire_Id(int $newhoraire_id)
    {
        $this->horaire_id = $newhoraire_id;
    }
    public function getJour(): string
    {
        return $this->jour;
    }
    public function setJour(string $newjour)
    {
        $this->jour = $newjour;
    }


    public function getOuverture(): string
    {
        return $this->heure_ouverture;
    }
    public function setOuverture(string $newOuverture)
    {
        $this->heure_ouverture = $newOuverture;
    }

    public function getFermeture(): string
    {
        return $this->heure_fermeture;
    }
    public function setFermeture(string $newheure_fermeture)
    {
        $this->heure_fermeture = $newheure_fermeture;
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