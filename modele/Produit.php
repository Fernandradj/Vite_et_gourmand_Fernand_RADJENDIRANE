<?php
class Produit
{
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
}
?>