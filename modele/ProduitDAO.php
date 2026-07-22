<?php
class ProduitDAO
{

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function getById(bool $loadRecord, int $produit_id, string $nom = "", string $type = "", string $photo = "")
    {

        $produitData = new Produit();
        $produitData->setID($produit_id);
        if ($loadRecord) {
            $sql = "SELECT Produit_Id, Nom, Type, Photo FROM produit WHERE Produit_Id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$produit_id]);
            $resultat = $stmt->fetch();
            if ($resultat) {
                $produitData->setNom($resultat["Nom"]);
                $produitData->setType($resultat["Type"]);
                if ($resultat["Photo"] == null) {
                    $produitData->setPhoto("");
                } else {
                    $produitData->setPhoto($resultat["Photo"]);
                }
            }
        } else {
            $produitData->setNom($nom);
            $produitData->setType($type);
            $produitData->setPhoto($photo);
        }
        return $produitData;
    }

    
    public function loadAllProduitByType(string $type, PDO $pdo): array
    {
        $sql = "SELECT Produit_Id, Nom, Type FROM produit WHERE Type = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$type]);
        $produit_list = $stmt->fetchAll();
        $produits = [];
        if ($produit_list) {
            foreach ($produit_list as $value) {
                $new_produit = $this->getById(false, $value["Produit_Id"], $value["Nom"], $value["Type"], "");
                array_push($produits, $new_produit);
            }
        }
        return $produits;
    }
    
    public function loadAllEntree(): array
    {
        return $this->loadAllProduitByType(Produit::TYPE_ENTREE, $this->pdo);
    }

    public function loadAllPlat(): array
    {
        return $this->loadAllProduitByType(Produit::TYPE_PLAT, $this->pdo);
    }

    public function loadAllDessert(): array
    {
        return $this->loadAllProduitByType(Produit::TYPE_DESSERT, $this->pdo);
    }

}
?>