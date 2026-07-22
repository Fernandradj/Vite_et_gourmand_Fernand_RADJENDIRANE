<?php
class MenuDAO
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getbyId(int $id)
    {
        $menuData = new Menu();
        $menuData->setId($id);

        $entrees = [];
        $plats = [];
        $desserts = [];
        $sql = "SELECT Menu_Id, Nom, Nombre_personne_minimum, Prix_par_personne, Regime, Theme, Description, Quantite_restante, Conditions FROM menu WHERE menu_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $menu = $stmt->fetch();
        if ($menu) {
            $menuData->setNom($menu["Nom"]);
            $menuData->setNombre_personne_minimum($menu["Nombre_personne_minimum"]);
            $menuData->setPrix_par_personne($menu["Prix_par_personne"]);
            $menuData->setRegime($menu["Regime"]);
            $menuData->setTheme($menu["Theme"]);
            $menuData->setDescription($menu["Description"]);
            $menuData->setQuantite_restante($menu["Quantite_restante"]);
            $menuData->setCondition($menu["Conditions"]);

        }


        $sql = "SELECT produit.Produit_Id, Nom, Type, Photo FROM produit JOIN composition ON produit.Produit_Id=composition.Produit_Id WHERE Menu_Id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $produit_list = $stmt->fetchAll();
        $new_produit_list = [];
        if ($produit_list) {
            foreach ($produit_list as $value) {
                $photo = $value["Photo"];
                if ($photo == null) {
                    $photo = "";
                }
                $produitDAO = new ProduitDAO($this->pdo);
                $new_produit = $produitDAO->getById(false, $value["Produit_Id"], $value["Nom"], $value["Type"], $photo);

                if ($value["Type"] == Produit::TYPE_ENTREE) {
                    array_push($entrees, $new_produit);
                } elseif ($value["Type"] == Produit::TYPE_PLAT) {
                    array_push($plats, $new_produit);
                } elseif ($value["Type"] == Produit::TYPE_DESSERT) {
                    array_push($desserts, $new_produit);
                }
            }
        }
        $menuData->setEntreeArray($entrees);
        $menuData->setPlatArray($plats);
        $menuData->setDessertArray($desserts);
        return $menuData;
    }
    
    public function loadMenus(): array
    {
        $sql = "SELECT Menu_Id, Nom, Nombre_personne_minimum, Prix_par_personne, Regime, Theme, Description, Quantite_restante, Conditions FROM menu";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([]);
        $resultat = $stmt->fetchAll();
        $menu = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_menu = $this->getbyId($value["Menu_Id"]);
                array_push($menu, $new_menu);
            }
        }
        return $menu;
    }

    public function reduireQuantiteMenu(int $menu_id): void
    {

        // 1. get quantity
        $sql = "SELECT Quantite_restante FROM menu WHERE Menu_Id =?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$menu_id]);
        $resultat = $stmt->fetch();
        if ($resultat) {
            $quantite_restante = $resultat["Quantite_restante"] - 1;

            // 2. update with new quantity
            $sql = "UPDATE menu SET Quantite_restante = ? WHERE Menu_Id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$quantite_restante, $menu_id]);
        }
    }

    public function saveMenu(int $menu_id, string $nom, int $nombre_personne_minimum, float $prix_par_personne, string $regime, string $theme, string $description, int $quantite_restante, string $condition, array $entrees, array $plats, array $desserts): Resultat
    {
        try {
            $sql = 'UPDATE menu SET Nom = ?, Nombre_personne_minimum = ?, Prix_par_personne = ?, Regime = ?, Theme = ?, Description = ?, Quantite_restante = ?, Conditions = ? WHERE Menu_Id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nom, $nombre_personne_minimum, $prix_par_personne, $regime, $theme, $description, $quantite_restante, $condition, $menu_id]);

            $sql = 'DELETE FROM composition WHERE Menu_Id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$menu_id]);

            foreach ($entrees as $entree) {
                $sql = 'INSERT INTO composition (Menu_Id, Produit_Id) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$menu_id, $entree]);
            }
            foreach ($plats as $plat) {
                $sql = 'INSERT INTO composition (Menu_Id, Produit_Id) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$menu_id, $plat]);
            }
            foreach ($desserts as $dessert) {
                $sql = 'INSERT INTO composition (Menu_Id, Produit_Id) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$menu_id, $dessert]);
            }

            return new Resultat(true, "Le menu a été mis à jour avec succès.");
        } catch (PDOException $e) {
            return new Resultat(false, "Erreur lors de la mise à jour du menu : " . $e->getMessage());
        }
    }

    public function creerMenu(string $nom, int $nombre_personne_minimum, float $prix_par_personne, string $regime, string $theme, string $description, int $quantite_restante, string $condition, array $entrees, array $plats, array $desserts): Resultat
    {
        try {
            $sql = 'INSERT INTO menu (Nom, Nombre_personne_minimum, Prix_par_personne, Regime, Theme, Description, Quantite_restante, Conditions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nom, $nombre_personne_minimum, $prix_par_personne, $regime, $theme, $description, $quantite_restante, $condition]);
            $menu_id = $this->loadLastMenuCreated();
            foreach ($entrees as $entree) {
                $sql = 'INSERT INTO composition (Menu_Id, Produit_Id) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$menu_id, $entree]);
            }
            foreach ($plats as $plat) {
                $sql = 'INSERT INTO composition (Menu_Id, Produit_Id) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$menu_id, $plat]);
            }
            foreach ($desserts as $dessert) {
                $sql = 'INSERT INTO composition (Menu_Id, Produit_Id) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$menu_id, $dessert]);
            }

            return new Resultat(true, "Le menu a été crée avec succès.");
        } catch (PDOException $e) {
            return new Resultat(false, "Erreur lors de la création du menu : " . $e->getMessage());
        }
    }

    public function loadLastMenuCreated(): int
    {
        $sql = "SELECT Menu_Id FROM menu ORDER BY Menu_Id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([]);
        $resultat = $stmt->fetch();
        if ($resultat) {
            return $resultat["Menu_Id"];
        }
        return 0;
    }

    public function loadMenuIdByName(string $nom): int
    {
        $sql = "SELECT Menu_Id FROM menu WHERE Nom = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nom]);
        $resultat = $stmt->fetch();
        if ($resultat) {
            return $resultat["Menu_Id"];
        }
        return 0;
    }
}


?>