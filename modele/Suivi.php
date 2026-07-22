<?php
class Suivi
{
    private int $id;
    private int $numero_commande;
    private string $statut;
    private string $date;

    private bool $done;

    

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int$newId)
    {
        $this->id =$newId;
    }

    public function getNumeroCommande(): string
    {
        return $this->numero_commande;
    }
     public function setNumeroCommande(int $newnumero_commande)
    {
        $this->numero_commande =$newnumero_commande;
    }


    public function getStatut(): string
    {
        return $this->statut;
    }
    public function setStatut(string $newStatut)
    {
        $this->statut = $newStatut;
    }


    public function getDate(): string
    {
        return substr($this->date, 0, 16);
    }
    public function setDate(string $newDate)
    {
        $this->date = $newDate;
    }

    public function getDone(): bool
    {
        return $this->done;
    }
     public function setDone(bool $newDone)
    {
        $this->done =$newDone;
    }
}


?>