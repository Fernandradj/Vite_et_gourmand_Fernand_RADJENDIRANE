<?php

class Commande {
    private PDO $pdo;
    private int $numero_commande;
    private string $date_commande;
    private string $date_prestation;
    private string $heure_livraison;
    private float $prix_menu;
    private float $prix_livraison;
    private string $statut;
    private bool $pret_material;
    private bool $restitution_material;
    private Utilisateur $utilisateur;
    private Menu $menu;
 

    public function __construct(int $id, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->numero_commande = $id;
        $sql = "SELECT Commande, numero_commande, date_commande, date_prestation, heure_livraison, prix_livraison, statut, pret_material, restitution_material, utilisateur, menu FROM commande WHERE Numero_commande = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $commande = $stmt->fetch();
        if ($commande) {
            $this->date_commande = $commande["date_commande"];
            $this->date_prestation = $commande["date_prestation"];
            $this->heure_livraison = $commande["heure_livraison"];
            $this->prix_livraison = $commande["prix_livraison"];
            $this->statut = $commande["statut"];
            $this->pret_material = $commande["pret_material"];
            $this->restitution_material = $commande["restitution_material"];

            $utilisateur_id = $commande["utilisateur"];
            $this->utilisateur = new Utilisateur($utilisateur_id, $pdo);

            $menu_id = $commande["menu"];
            $this->menu = new Menu($menu_id, $pdo);
        }
    }


}


?>