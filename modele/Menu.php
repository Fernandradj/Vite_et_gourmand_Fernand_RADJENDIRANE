<?php

class Menu
{
    private int $menu_id;
    private string $nom;
    private int $nombre_personne_minimum;
    private float $prix_par_personne;
    private string $regime;
    private string $theme;
    private string $description;
    private int $quantite_restante;
    private string $condition;

    private array $entrees;
    private array $plats;
    private array $desserts;



    

    public function getId(): int
    {
        return $this->menu_id;
    }
    public function setId(int $newmenuId)
    {
        $this->menu_id = $newmenuId;
    }

    public function getNom(): string
    {

        return $this->nom;
    }
       public function setNom(string $newnom)
    {

        $this->nom = $newnom;
    }
    public function getDescription(): string
    {

        return $this->description;
    }
    public function setDescription(string $newdescription)
    {

        $this->description = $newdescription;
    }
    public function getNombre_personne_minimum(): string
    {

        return $this->nombre_personne_minimum;
    }
    public function setNombre_personne_minimum(int $newnombre_personne_minimum)
    {

        $this->nombre_personne_minimum = $newnombre_personne_minimum;
    }
    public function getPrix_par_personne(): float
    {

        return $this->prix_par_personne;
    }
     public function setPrix_par_personne(float $newprix_par_personne)
    {

        $this->prix_par_personne = $newprix_par_personne;
    }
    public function getRegime(): string
    {

        return $this->regime;
    }
    public function setRegime(string $newregime)
    {

        $this->regime = $newregime;
    }
    public function getTheme(): string
    {

        return $this->theme;
    }
    public function setTheme(string $newtheme)
    {

        $this->theme = $newtheme;
    }
    public function getQuantite_restante(): string
    {

        return $this->quantite_restante;
    }
    public function setQuantite_restante(int $newquantite_restante)
    {

        $this->quantite_restante = $newquantite_restante;
    }
    public function getCondition(): string
    {

        return $this->condition;

    }
    public function setCondition(string $newcondition)
    {

        $this->condition = $newcondition;

    }
    public function getEntree(): string
    {
        $entreeString = "";
        foreach ($this->entrees as $entree) {
            $entreeString = $entreeString . "/" . $entree->getNom();
        }
        $entreeString = ltrim($entreeString, '/');
        return $entreeString;
    }
     public function setEntree(string $newentreestring)
    {
        $entreeString = "";
        foreach ($this->entrees as $entree) {
            $entreeString = $entreeString . "/" . $entree->getNom();
        }
        $entreeString = ltrim($entreeString, '/');
        $this->$entreeString = $newentreestring;
    }
    public function getEntreeArray()
    {
        return $this->entrees;
    }
    public function setEntreeArray(array $newentrees)
    {
        $this->entrees = $newentrees;
    }


    public function getPlat(): string
    {
        $platString = "";
        foreach ($this->plats as $plat) {
            $platString = $platString . "/" . $plat->getNom();
        }
        $platString = ltrim($platString, '/');
        return $platString;
    }
    public function setPlat(string $newplatString)
    {
        $platString = "";
        foreach ($this->plats as $plat) {
            $platString = $platString . "/" . $plat->getNom();
        }
        $platString = ltrim($platString, '/');
        $this->$platString = $newplatString;
    }
    public function getPlatArray()
    {
        return $this->plats;
    }
     public function setPlatArray(array $newplats)
    {
        $this->plats = $newplats;
    }

    public function getDessert(): string
    {
        $dessertString = "";
        foreach ($this->desserts as $dessert) {
            $dessertString = $dessertString . "/" . $dessert->getNom();
        }
        $dessertString = ltrim($dessertString, '/');
        return $dessertString;
    }
    public function setDessert(string $newdessertsString)
    {
        $dessertString = "";
        foreach ($this->desserts as $dessert) {
            $dessertString = $dessertString . "/" . $dessert->getNom();
        }
        $dessertString = ltrim($dessertString, '/');
        $this->$dessertString = $newdessertsString;
    }
    public function getDessertArray()
    {
        return $this->desserts;
    }
public function setDessertArray(array $newdesserts)
    {
        $this->desserts =$newdesserts;
    }

    public function menuHasEntree(Produit $entree): bool
    {
        foreach ($this->entrees as $item) {
            if ($item->getID() == $entree->getID()) {
                return true;
            }
        }
        return false;
    }
    

    public function menuHasPlat(Produit $plat): bool
    {
        foreach ($this->plats as $item) {
            if ($item->getID() == $plat->getID()) {
                return true;
            }
        }
        return false;
    }

    public function menuHasDessert(Produit $dessert): bool
    {
        foreach ($this->desserts as $item) {
            if ($item->getID() == $dessert->getID()) {
                return true;
            }
        }
        return false;
    }


    //  public function getEntree(): string
    // {

    //     return $this->entree;

    // }
    //  public function get(): string
    // {

    //     return $this->condition;

    // }
    

}
?>