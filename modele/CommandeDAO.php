<?php

class CommandeDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById(int $id)
    {
        $commandeData = new Commande();
        $commandeData->setNumeroCommande($id);
        $sql = "SELECT Numero_commande, Nombre_personne, Date_commande, Date_Heure_livraison, Prix_commande, Prix_livraison, Prix_distance_livraison, Reduction, Prix_totale, Statut, Pret_materiel, Restitution_materiel, Adresse_livraison, Entree_Id, Plat_Id, Dessert_Id, Utilisateur_Id, Menu_Id FROM commande WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $commande = $stmt->fetch();
        if ($commande) {
            $commandeData->setDateCommande($commande["Date_commande"]);
            $commandeData->setNombrePersonne($commande["Nombre_personne"]);
            $commandeData->setDateHeureLivraison($commande["Date_Heure_livraison"]);
            $commandeData->setPrixCommande($commande["Prix_commande"]);
            $commandeData->setPrixLivraison($commande["Prix_livraison"]);
            $commandeData->setPrixDistanceLivraison($commande["Prix_distance_livraison"]);
            $commandeData->setReduction($commande["Reduction"]);
            $commandeData->setPrixTotale($commande["Prix_totale"]);
            $commandeData->setStatut($commande["Statut"]);
            if ($commande["Pret_materiel"] == 1) {
                $commandeData->setPret_materiel(true);
            } else {
                $commandeData->setPret_materiel(false);
            }
            if ($commande["Restitution_materiel"] == 1) {
                $commandeData->setRestitution_materiel(true);
            } else {
                $commandeData->setRestitution_materiel(false);
            }
            $commandeData->setAdresseLivraison($commande["Adresse_livraison"]);

            $entree_id = $commande["Entree_Id"];
            $produitDAO = new ProduitDAO($this->pdo);
            $commandeData->setEntree($produitDAO->getById(true, $entree_id, "", "", ""));
            $plat_id = $commande["Plat_Id"];
            $commandeData->setPlat($produitDAO->getById(true, $plat_id, "", "", ""));
            $dessert_id = $commande["Dessert_Id"];
            $commandeData->setDessert($produitDAO->getById(true, $dessert_id, "", "", ""));

            $utilisateur_id = $commande["Utilisateur_Id"];
            $userDAO = new UtilisateurDAO($this->pdo);
            $commandeData->setUtilisateur($userDAO->getById(true, $utilisateur_id));

            $menu_id = $commande["Menu_Id"];
            $menuDAO = new MenuDAO($this->pdo);
            $commandeData->setMenu($menuDAO->getbyId($menu_id));
            $suiviDAO = new SuiviDAO($this->pdo);
            $commandeData->setSuivis($suiviDAO->loadSuivisByCommandeId($id));
        }

        return $commandeData;
    }

    public function loadCommandeUtilisateur(int $Utilisateur_Id)
    {
        $sql = "SELECT Numero_commande, Date_commande, Date_Heure_livraison, Prix_totale, Statut, Pret_materiel, Restitution_materiel, Utilisateur_Id, Menu_Id FROM commande WHERE Utilisateur_Id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$Utilisateur_Id]);
        $resultat = $stmt->fetchAll();

        $commandes = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_commande = $this->getById($value["Numero_commande"]);
                array_push($commandes, $new_commande);
            }
        }
        return $commandes;
    }

    public function loadAllCommande()
    {
        $sql = "SELECT Numero_commande, Date_commande, Date_Heure_livraison, Prix_totale, Statut, Pret_materiel, Restitution_materiel, Utilisateur_Id, Menu_Id FROM commande";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll();

        $commandes = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_commande = $this->getById($value["Numero_commande"]);
                array_push($commandes, $new_commande);
            }
        }
        return $commandes;
    }

    public function creerSuivi(int $numero_commande, string $statut): void
    {
        $sql = "INSERT INTO suivi (Numero_commande, Statut, Date) VALUES (?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$numero_commande, $statut]);
    }
    public function saveCommande(int $nombre_pers, string $date_cmd, string $date_date_heure_liv, float $totale_cmd, float $prix_liv, float $prix_distance_livraison, float $reduction, float $prix_totale, string $statut, int $utilisateur_id, int $menu_id, int $entree_id, int $plat, int $dessert_id, string $addresse_livraison): Resultat
    {
        $sql = "INSERT INTO commande (`Nombre_personne`, `Date_commande`, `Date_Heure_livraison`, `Prix_commande`, `Prix_livraison`, `Statut`, `Utilisateur_Id`, `Menu_Id`, `Entree_Id`, `Plat_Id`, `Dessert_Id`, `Adresse_livraison`, `Reduction`, `Prix_totale`, `Prix_distance_livraison`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [$nombre_pers, $date_cmd, $date_date_heure_liv, $totale_cmd, $prix_liv, $statut, $utilisateur_id, $menu_id, $entree_id, $plat, $dessert_id, $addresse_livraison, $reduction, $prix_totale, $prix_distance_livraison]);

            $numero_commande = $this->loadLastCommandeOfUser($utilisateur_id);
            $menuDAO = new MenuDAO($this->pdo);
            $menuDAO->reduireQuantiteMenu($menu_id);
            $this->creerSuivi($numero_commande, Commande::COMMANDE_STATUT_COMMANDE);

            return new Resultat(true, "Votre commande a bien été enregistrée.");
        } catch (PDOException $e) {
            echo $e;
            return new Resultat(false, "Une erreur s'est produite lors de l'enregistrement, veuillez réessayer plus tard.");
        }
    }
    public function annulerCommande(int $numero_commande): Resultat
    {
        $sql = "UPDATE commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_ANNULE, $numero_commande]);

            $this->creerSuivi($numero_commande, Commande::COMMANDE_STATUS_ANNULE);

            return new Resultat(true, "Votre commande a bien été annulée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de l'annulation, veuillez réessayer plus tard.");
        }
    }

    public function modifierCommande(int $numero_commande, string $adresse, string $dateHeure, int $plat_id, int $dessert_id, int $entree_id, int $nombrePersonne, int $pret_materiel, int $restitution_materiel, float $totale_cmd, float $prix_liv, float $prix_distance_livraison, float $reduction, float $prix_totale): Resultat
    {
        $sql = "UPDATE commande SET Adresse_livraison = ?, Date_Heure_livraison = ?, Plat_Id = ?, Dessert_Id = ?, Entree_Id = ?, Nombre_personne = ?, Pret_materiel = ?, Restitution_materiel = ?, Prix_commande = ?, Prix_livraison = ?, Prix_distance_livraison = ?, Reduction = ?, Prix_totale = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [$adresse, $dateHeure, $plat_id, $dessert_id, $entree_id, $nombrePersonne, $pret_materiel, $restitution_materiel, $totale_cmd, $prix_liv, $prix_distance_livraison, $reduction, $prix_totale, $numero_commande]);
            return new Resultat(true, "Votre commande a bien été modifiée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la modification de votre commande.");
        }
    }

    public function validerCommande(int $numero_commande): Resultat
    {
        $sql = "UPDATE commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_VALIDE, $numero_commande]);
            $this->creerSuivi($numero_commande, Commande::COMMANDE_STATUS_VALIDE);
            return new Resultat(true, "La commande a bien été validée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la validation de la commande.");
        }
    }


    public function preparerCommande(int $numero_commande): Resultat
    {
        $sql = "UPDATE commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_PREPARATION, $numero_commande]);
            $this->creerSuivi($numero_commande, Commande::COMMANDE_STATUS_PREPARATION);
            return new Resultat(true, "La commande a bien été mise en préparation.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la mise en préparation de la commande.");
        }
    }

    public function expedierCommande(int $numero_commande): Resultat
    {
        $sql = "UPDATE commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_EXPEDIE, $numero_commande]);
            $this->creerSuivi($numero_commande, Commande::COMMANDE_STATUS_EXPEDIE);
            return new Resultat(true, "La commande a bien été expédiée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de l'expédition de la commande.");
        }
    }

    public function livrerCommande(int $numero_commande, int $pret_materiel): Resultat
    {
        $sql = "UPDATE commande SET Statut = ?, Pret_materiel = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);

        try {
            $newStatut = Commande::COMMANDE_STATUS_TERMINE;
            if ($pret_materiel == 1) {
                $newStatut = Commande::COMMANDE_STATUS_ATTENTE_RETOUR;
            }
            $stmt->execute(params: [$newStatut, $pret_materiel, $numero_commande]);
            $this->creerSuivi($numero_commande, $newStatut);
            if ($newStatut == Commande::COMMANDE_STATUS_TERMINE) {
                $this->saveTerminatedCommande($numero_commande);
            }
            return new Resultat(true, "La commande a bien été livrée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la livraison de la commande.");
        }
    }
    public function terminerCommande(int $numero_commande, int $pret_materiel): Resultat
    {
        $sql = "UPDATE commande SET Statut = ?, Restitution_materiel = ? WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);
        try {
            $restitution_materiel = 0;
            if ($pret_materiel == 1) {
                $restitution_materiel = 1;
            }
            $stmt->execute(params: [Commande::COMMANDE_STATUS_TERMINE, $restitution_materiel, $numero_commande]);
            $this->creerSuivi($numero_commande, Commande::COMMANDE_STATUS_TERMINE);
            $this->saveTerminatedCommande($numero_commande);

            // Vous pouvez récupérer l'ID généré automatiquement par MongoDB
            // $idGenere = $resultat->getInsertedId();
            // echo "Document inséré avec succès ! ID : " . $idGenere;



            return new Resultat(true, "La commande a bien été terminée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la terminaison de la commande.");
        }
    }

    private function saveTerminatedCommande(int $numero_commande)
    {
        $sql = "SELECT Nom, menu.Menu_Id menuId, Numero_commande, Prix_totale FROM commande JOIN menu ON menu.Menu_Id = commande.Menu_Id WHERE Numero_commande = ?";
        $stmt = $this->pdo->prepare(query: $sql);
        $stmt->execute(params: [$numero_commande]);
        $cmd = $stmt->fetch();

        $client = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $client->selectCollection('Vite_et_Gourmand', 'Commande');

        // Préparation des données du document
        $nouvelArticle = [
            'Menu_nom' => $cmd['Nom'],
            'Prix_totale' => (float) $cmd['Prix_totale'],
            'Date_commande' => new MongoDB\BSON\UTCDateTime(),
            'Numero_commande' => $cmd['Numero_commande'],
            'Menu_id' => $cmd['menuId']

        ];

        $resultat = $collection->insertOne($nouvelArticle);
    }

    public function loadLastCommandeOfUser(int $utilisateur_id): int
    {
        $sql = "SELECT Numero_commande FROM commande WHERE Utilisateur_Id = ? ORDER BY Numero_commande DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(params: [$utilisateur_id]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return $resultat["Numero_commande"];
        }
        return 0;
    }

    public function loadChiffresMenus(string $menu, string $startDate, string $endDate): array
    {
        $data = [];
        $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        // $db = $client->Vite_et_Gourmand;
        // $collection = $db->Vente;
        // $ventes = $collection->find();
        // foreach ($ventes as $vente) {
        //     // echo "name : " . $vente['name'] . " nb : " . $vente['nombreDeVentes'] . " ca : " . $vente['chiffreAffaire'] . "<br>";
        // $input = [];
        // $input['prix'] = $vente['chiffreAffaire'];
        // $input['nbCommande'] = $vente['nombreDeVentes'];
        // $data[$vente['name']] = $input;
        // }

        $matchFilter = [];

        // Filtre sur le nom (si non vide)
        if (!empty($menu)) {
            $matchFilter['Menu_nom'] = $menu;
        }

        // Filtre sur les dates (on gère le cas où l'une, l'autre, ou les deux sont remplies)
        if (!empty($startDate) || !empty($endDate)) {
            $matchFilter['Date_commande'] = [];

            if (!empty($startDate)) {
                $matchFilter['Date_commande']['$gte'] = new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000);
            }

            if (!empty($endDate)) {
                $matchFilter['Date_commande']['$lte'] = new MongoDB\BSON\UTCDateTime(strtotime($endDate . " 23:59:59") * 1000);
            }
        }


        // 3. Construction du pipeline d'agrégation
        $pipeline = [];

        // On ajoute l'étape $match SEULEMENT si au moins un filtre a été saisi
        if (!empty($matchFilter)) {
            $pipeline[] = ['$match' => $matchFilter];
        }

        // L'étape $group reste requise pour faire la somme et le regroupement
        $pipeline[] = [
            '$group' => [
                '_id' => '$Menu_nom',
                'total_docs' => ['$sum' => 1],
                'somme_prix' => ['$sum' => '$Prix_totale']
            ]
        ];


        // 4. Exécution de la commande
        $command = new MongoDB\Driver\Command([
            'aggregate' => 'Commande',
            'pipeline' => $pipeline,
            'cursor' => new stdClass(),
        ]);

        try {
            $cursor = $client->executeCommand('Vite_et_Gourmand', $command);

            // 5. Affichage des résultats
            foreach ($cursor as $document) {
                // Si aucun filtre n'est mis, _id affichera chaque nom présent dans la base
                // echo "<h3>Résultat pour le groupe : " . ($document->_id ?? 'Sans nom') . "</h3>";
                // echo "Nombre d'articles : " . $document->total_docs . "<br>";
                // echo "Somme totale des prix : " . $document->somme_prix . " €<br>";
                // echo "-----------------------------------<br>";
                $input = [];
                $input['prix'] = $document->somme_prix;
                $input['nbCommande'] = $document->total_docs;
                $data[$document->_id] = $input;
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            // echo "Erreur : " . $e->getMessage();
        }

        // $sql = "SELECT commande.Menu_Id menuId, menu.Nom menuNom, COUNT(Numero_commande) nbCommande, SUM(Prix_totale) prix FROM commande JOIN menu ON commande.Menu_Id = menu.Menu_Id WHERE Statut = ?";
        // $params = [Commande::COMMANDE_STATUS_TERMINE];
        // if (($startDate != null) && ($startDate != "")) {
        //     $sql .= " AND Date_commande >= ?";
        //     array_push($params, $startDate);
        // }
        // if (($endDate != null) && ($endDate != "")) {
        //     $sql .= " AND Date_commande <= ?";
        //     array_push($params, $endDate);
        // }
        // if (($menu != null) && ($menu != "")) {
        //     $sql .= " AND menu.Nom = ?";
        //     array_push($params, $menu);
        // }
        // $sql .= " GROUP BY commande.Menu_Id, menu.Nom";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute(params: $params);
        // $resultat = $stmt->fetchAll();

        // if ($resultat) {
        //     foreach ($resultat as $key => $value) {
        //         // echo $value['menuNom'] . ' - ' . $value['prix'] . ' - ' . $value['nbCommande'];
        //         $input = [];
        //         $input['prix'] = $value['prix'];
        //         $input['nbCommande'] = $value['nbCommande'];
        //         $data[$value['menuNom']] = $input;
        //     }
        // }
        return $data;
    }


    public function loadMenus(): array
    {
        $sql = "SELECT menu.Nom menuNom FROM commande JOIN menu ON commande.Menu_Id = menu.Menu_Id WHERE Statut = ? GROUP BY menu.Nom";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(params: [Commande::COMMANDE_STATUS_TERMINE]);
        $resultat = $stmt->fetchAll();
        $data = [];
        if ($resultat) {
            foreach ($resultat as $key => $value) {
                array_push($data, $value['menuNom']);
            }
        }
        return $data;
    }
}

?>