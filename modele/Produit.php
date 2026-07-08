<?php
class Produit
{

    const TYPE_ENTREE = "Entrée";
    const TYPE_PLAT = "Plat";
    const TYPE_DESSERT = "Dessert";

    const REGIME_OPTIONS = ["Classique", "Végétarien", "Vegan"];

    const THEME_OPTIONS = ["Noël", "Pâques", "Classique", "Evénement"];

    private int $produit_id;
    private string $nom;
    private string $type;
    private string $photo;

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $newNom)
    {
        $this->nom = $newNom;
    }
    public function getID(): string
    {
        return $this->produit_id;
    }

    public function setID(int $newId) {
        $this->produit_id = $newId;
    }

    public function getType(): string
    {
        return $this->type;
    }
    
    public function setType(string $newType)
    {
        $this->type = $newType;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $newPhoto)
    {
        $this->photo = $newPhoto;
    }
}
?>