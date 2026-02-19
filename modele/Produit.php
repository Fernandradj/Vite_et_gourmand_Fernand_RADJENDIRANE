<?php
class Produit
{

    const TYPE_ENTREE = "Entrée";
    const TYPE_PLAT = "Plat";
    const TYPE_DESSERT = "Dessert";

    const REGIME_OPTIONS = ["Classique", "Végétarien", "Vegan"];

    const THEME_OPTIONS = ["Noël", "Pâques", "Classique", "Evénement"];

    private PDO $pdo;
    private int $produit_id;
    private string $nom;
    private string $type;
    private string $photo;

    public function __construct(bool $loadRecord, int $produit_id, string $nom = "", string $type = "", string $photo = "", PDO $pdo)
    {

        $this->pdo = $pdo;
        $this->produit_id = $produit_id;
        if ($loadRecord) {
            $sql = "SELECT Produit_Id, Nom, Type, Photo FROM produit WHERE Produit_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$produit_id]);
            $resultat = $stmt->fetch();
            if ($resultat) {
                $this->nom = $resultat["Nom"];
                $this->type = $resultat["Type"];
                if ($resultat["Photo"] == null) {
                    $this->photo = "";
                } else {
                    $this->photo = $resultat["Photo"];
                }
            }
        } else {
            $this->nom = $nom;
            $this->type = $type;
            $this->photo = $photo;
        }
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function getID(): string
    {
        return $this->produit_id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public static function loadAllProduitByType(string $type, PDO $pdo): array
    {
        $sql = "SELECT Produit_Id, Nom, Type FROM produit WHERE Type = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$type]);
        $produit_list = $stmt->fetchAll();
        $produits = [];
        if ($produit_list) {
            foreach ($produit_list as $value) {
                $new_produit = new Produit(false, $value["Produit_Id"], $value["Nom"], $value["Type"], "",  $pdo);
                array_push($produits, $new_produit);
            }
        }
        return $produits;
    }
    
    public static function loadAllEntree($pdo): array
    {
        return Produit::loadAllProduitByType(Produit::TYPE_ENTREE, $pdo);
    }

    public static function loadAllPlat($pdo): array
    {
        return Produit::loadAllProduitByType(Produit::TYPE_PLAT, $pdo);
    }

    public static function loadAllDessert($pdo): array
    {
        return Produit::loadAllProduitByType(Produit::TYPE_DESSERT, $pdo);
    }
}
?>