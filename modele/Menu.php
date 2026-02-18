<?php

class Menu
{
    private PDO $pdo;
    private int $menu_id;
    private string $nom;
    private int $nombre_personne_minimum;
    private int $prix_par_personne;
    private string $regime;
    private string $theme;
    private string $description;
    private int $quantite_restante;
    private string $condition;

    private array $entrees;
    private array $plats;
    private array $desserts;



    public function __construct(int $id, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->menu_id = $id;
        $this->entrees = [];
        $this->plats = [];
        $this->desserts = [];
        $sql = "SELECT Menu_Id, Nom, Nombre_personne_minimum, Prix_par_personne, Regime, Theme, Description, Quantite_restante, Conditions FROM menu WHERE menu_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $menu = $stmt->fetch();
        if ($menu) {
            $this->nom = $menu["Nom"];
            $this->nombre_personne_minimum = $menu["Nombre_personne_minimum"];
            $this->prix_par_personne = $menu["Prix_par_personne"];
            $this->regime = $menu["Regime"];
            $this->theme = $menu["Theme"];
            $this->description = $menu["Description"];
            $this->quantite_restante = $menu["Quantite_restante"];
            $this->condition = $menu["Conditions"];

        }


        $sql = "SELECT Produit.Produit_Id, Nom, Type, Photo FROM produit JOIN Composition ON produit.Produit_Id=composition.Produit_Id WHERE Menu_Id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $produit_list = $stmt->fetchAll();
        $new_produit_list = [];
        if ($produit_list) {
            foreach ($produit_list as $value) {
                $photo = $value["Photo"];
                if ($photo == null) {
                    $photo = "";
                }
                $new_produit = new Produit(false, $value["Produit_Id"], $value["Nom"], $value["Type"], $photo,  $pdo);

                if ($value["Type"] == "Entrée") {
                    array_push($this->entrees, $new_produit);
                } elseif ($value["Type"] == "Plat") {
                    array_push($this->plats, $new_produit);
                } elseif ($value["Type"] == "Dessert") {
                    array_push($this->desserts, $new_produit);
                }


            }
        }

    }

    public function getId(): int
    {
        return $this->menu_id;
    }

    public function getNom(): string
    {

        return $this->nom;
    }
    public function getDescription(): string
    {

        return $this->description;
    }
    public function getNombre_personne_minimum(): string
    {

        return $this->nombre_personne_minimum;
    }
    public function getPrix_par_personne(): string
    {

        return $this->prix_par_personne;
    }
    public function getRegime(): string
    {

        return $this->regime;
    }
    public function getTheme(): string
    {

        return $this->theme;
    }
    public function getQuantite_restante(): string
    {

        return $this->quantite_restante;
    }
    public function getCondition(): string
    {

        return $this->condition;

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
    public function getEntreeArray()
    {
        return $this->entrees;
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
    public function getPlatArray()
    {
        return $this->plats;
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
    public function getDessertArray()
    {
        return $this->desserts;
    }




    //  public function getEntree(): string
    // {

    //     return $this->entree;

    // }
    //  public function get(): string
    // {

    //     return $this->condition;

    // }
    public static function loadMenus(PDO $pdo): array
    {
        $sql = "SELECT Menu_Id, Nom, Nombre_personne_minimum, Prix_par_personne, Regime, Theme, Description, Quantite_restante, Conditions FROM menu";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([]);
        $resultat = $stmt->fetchAll();
        $menu = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_menu = new Menu($value["Menu_Id"], $pdo);
                array_push($menu, $new_menu);
            }
        }
        return $menu;
    }
    public static function reduireQuantiteMenu(int $menu_id, PDO $pdo): void
    {

        // 1. get quantity
        $sql = "SELECT Quantite_restante FROM Menu WHERE Menu_Id =?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$menu_id]);
        $resultat = $stmt->fetch();
        if ($resultat) {
            $quantite_restante = $resultat["Quantite_restante"] - 1;

            // 2. update with new quantity
            $sql = "UPADTE Menu SET = Quantite_restante = " . $quantite_restante . " WHERE Menu_Id = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$menu_id]);
        }


    }
}


?>