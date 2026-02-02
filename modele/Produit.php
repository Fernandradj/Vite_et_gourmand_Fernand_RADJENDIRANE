<?php
class Produit
{
    private PDO $pdo;
    private int $produit_id;
    private string $nom;
    private string $type;
    private string $photo;

    public function __construct(int $produit_id, string $nom, string $type, string $photo, PDO $pdo)
    {

        $this->pdo = $pdo;
        $this->produit_id = $produit_id;
        $this->nom = $nom;
        $this->type = $type;
        $this->photo = $photo;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
}
?>