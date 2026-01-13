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
    


    public function __construct(int $id, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->menu_id = $id;
        $sql = "SELECT menu, nom, nombre_personne_minimum, prix_par_personne, regime, theme, description, quantite_restante, condition FROM menu WHERE menu_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $menu = $stmt->fetch();
        if ($menu) {
            $this->nom = $menu["nom"];
            $this->nombre_personne_minimum = $menu["nombre_personne_minimum"];
            $this->prix_par_personne = $menu["prix_par_personne"];
            $this->regime = $menu["regime"];
            $this->theme = $menu["theme"];
            $this->description = $menu["description"];
            $this->quantite_restante = $menu["quantite_restante"];
            $this->condition = $menu["condition"];
        }
    }


}


?>