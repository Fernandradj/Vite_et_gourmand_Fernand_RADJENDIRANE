<?php

use Dom\Text;
use LDAP\Result;

class Commande
{

    public const COMMANDE_STATUT_COMMANDE = 'Commandé';

    public const COMMANDE_STATUS_ANNULE = 'Annulé';

    public const COMMANDE_STATUS_VALIDE = 'Validé';

    public const COMMANDE_STATUS_PREPARATION = 'En cours de préparation';

    public const COMMANDE_STATUS_EXPEDIE = 'Expédié';

    public const COMMANDE_STATUS_ATTENTE_RETOUR = 'En attente de retour matériel';

    public const COMMANDE_STATUS_TERMINE = 'Terminé';

    public const ACTION_COMMANDER = "Commander";
    public const ACTION_MODIFIER = "Modifier";
    public const ACTION_ANNULER = "Annuler";
    public const ACTION_VALIDER = "Valider";
    public const ACTION_PREPARER = "Préparer";
    public const ACTION_EXPEDIER = "Expédier";
    public const ACTION_LIVRER = "Livrer";
    public const ACTION_TERMINER = "Terminer";
    public const ACTION_NOTER = "Noter";

    private PDO $pdo;
    private int $numero_commande;

    private int $nombre_personne;
    private string $date_commande;
    private string $date_heure_livraison;
    private float $prix_commande;
    private float $prix_livraison;
    private float $prix_distance_livraison;
    private float $reduction;
    private float $prix_totale;
    private string $statut;
    private bool $pret_materiel;
    private bool $restitution_materiel;

    private Produit $entree;
    private Produit $plat;
    private Produit $dessert;
    private Utilisateur $utilisateur;
    private Menu $menu;

    private array $suivis;

    private string $adresse_livraison;

    private int $quantite_restante;
    public function __construct(int $id, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->numero_commande = $id;
        $sql = "SELECT Numero_commande, Nombre_personne, Date_commande, Date_Heure_livraison, Prix_commande, Prix_livraison, Prix_distance_livraison, Reduction, Prix_totale, Statut, Pret_materiel, Restitution_materiel, Adresse_livraison, Entree_Id, Plat_Id, Dessert_Id, Utilisateur_Id, Menu_Id FROM commande WHERE Numero_commande = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $commande = $stmt->fetch();
        if ($commande) {
            $this->date_commande = $commande["Date_commande"];
            $this->nombre_personne = $commande["Nombre_personne"];
            $this->date_heure_livraison = $commande["Date_Heure_livraison"];
            $this->prix_commande = $commande["Prix_commande"];
            $this->prix_livraison = $commande["Prix_livraison"];
            $this->prix_distance_livraison = $commande["Prix_distance_livraison"];
            $this->reduction = $commande["Reduction"];
            $this->prix_totale = $commande["Prix_totale"];
            $this->statut = $commande["Statut"];
            if ($commande["Pret_materiel"] == 1) {
                $this->pret_materiel = true;
            } else {
                $this->pret_materiel = false;
            }
            if ($commande["Restitution_materiel"] == 1) {
                $this->restitution_materiel = true;
            } else {
                $this->restitution_materiel = false;
            }
            $this->adresse_livraison = $commande["Adresse_livraison"];

            $entree_id = $commande["Entree_Id"];
            $this->entree = new Produit(true, $entree_id, "", "", "", pdo: $pdo);
            $plat_id = $commande["Plat_Id"];
            $this->plat = new Produit(true, $plat_id, "", "", "", pdo: $pdo);
            $dessert_id = $commande["Dessert_Id"];
            $this->dessert = new Produit(true, $dessert_id, "", "", "", pdo: $pdo);
            
            $utilisateur_id = $commande["Utilisateur_Id"];
            $this->utilisateur = new Utilisateur($utilisateur_id, $pdo);

            $menu_id = $commande["Menu_Id"];
            $this->menu = new Menu($menu_id, $pdo);

            $this->suivis = Suivi::loadSuivisByCommandeId($id, $pdo);
        }
    }
    public function getMenu(): Menu
    {
        return $this->menu;
    }
    
    public function getSuivis(): array
    {
        return $this->suivis;
    }

    public function getFullSuivi(): array {
        return Suivi::loadFullSuivi($this);
    }

    public static function loadCommandeUtilisateur(int $Utilisateur_Id, PDO $pdo)
    {
        $sql = "SELECT Numero_commande, Date_commande, Date_Heure_livraison, Prix_totale, Statut, Pret_materiel, Restitution_materiel, Utilisateur_Id, Menu_Id FROM commande WHERE Utilisateur_Id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Utilisateur_Id]);
        $resultat = $stmt->fetchAll();

        $commandes = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_commande = new Commande($value["Numero_commande"], $pdo);
                array_push($commandes, $new_commande);
            }
        }
        return $commandes;
    }

    public static function creerSuivi(int $numero_commande, string $statut, PDO $pdo): void
    {
        $sql = "INSERT INTO suivi (Numero_commande, Statut, Date) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$numero_commande, $statut]);
    }

    public static function saveCommande(int $nombre_pers, string $date_cmd, string $date_date_heure_liv, float $totale_cmd, float $prix_liv, float $prix_distance_livraison, float $reduction, float $prix_totale, string $statut, int $utilisateur_id, int $menu_id, int $entree_id, int $plat, int $dessert_id, string $addresse_livraison, PDO $pdo): Resultat
    {
        $sql = "INSERT INTO Commande (`Nombre_personne`, `Date_commande`, `Date_Heure_livraison`, `Prix_commande`, `Prix_livraison`, `Statut`, `Utilisateur_Id`, `Menu_Id`, `Entree_Id`, `Plat_Id`, `Dessert_Id`, `Adresse_livraison`, `Reduction`, `Prix_totale`, `Prix_distance_livraison`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,? )";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [$nombre_pers, $date_cmd, $date_date_heure_liv, $totale_cmd, $prix_liv, $statut, $utilisateur_id, $menu_id, $entree_id, $plat, $dessert_id, $addresse_livraison, $reduction, $prix_totale, $prix_distance_livraison]);

            $numero_commande = Commande::loadLastCommandeOfUser($utilisateur_id, $pdo);
            Commande::creerSuivi($numero_commande, Commande::COMMANDE_STATUT_COMMANDE, $pdo);

            return new Resultat(true, "Votre commande a bien été enregistrée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de l'enregistrement, veuillez réessayer plus tard.");
        }
    }

    public static function annulerCommande(int $numero_commande, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_ANNULE, $numero_commande]);

            Commande::creerSuivi($numero_commande, Commande::COMMANDE_STATUS_ANNULE, $pdo);

            return new Resultat(true, "Votre commande a bien été annulée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de l'annulation, veuillez réessayer plus tard.");
        }
    }

    public static function modifierCommande(int $numero_commande, string $adresse, string $dateHeure, int $plat_id, int $dessert_id, int $entree_id, int $nombrePersonne, int $pret_materiel, int $restitution_materiel, float $totale_cmd, float $prix_liv, float $prix_distance_livraison, float $reduction, float $prix_totale, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Adresse_livraison = ?, Date_Heure_livraison = ?, Plat_Id = ?, Dessert_Id = ?, Entree_Id = ?, Nombre_personne = ?, Pret_materiel = ?, Restitution_materiel = ?, Prix_commande = ?, Prix_livraison = ?, Prix_distance_livraison = ?, Reduction = ?, Prix_totale = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [$adresse, $dateHeure, $plat_id, $dessert_id, $entree_id, $nombrePersonne, $pret_materiel, $restitution_materiel, $totale_cmd, $prix_liv, $prix_distance_livraison, $reduction, $prix_totale, $numero_commande]);
            return new Resultat(true, "Votre commande a bien été modifiée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la modification de votre commande.");
        }
    }

    public static function validerCommande(int $numero_commande, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_VALIDE, $numero_commande]);
            Commande::creerSuivi($numero_commande, Commande::COMMANDE_STATUS_VALIDE, $pdo);
            return new Resultat(true, "La commande a bien été validée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la validation de la commande.");
        }
    }

    public static function preparerCommande(int $numero_commande, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_PREPARATION, $numero_commande]);
            Commande::creerSuivi($numero_commande, Commande::COMMANDE_STATUS_PREPARATION, $pdo);
            return new Resultat(true, "La commande a bien été mise en préparation.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la mise en préparation de la commande.");
        }
    }

    public static function expedierCommande(int $numero_commande, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Statut = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $stmt->execute(params: [Commande::COMMANDE_STATUS_EXPEDIE, $numero_commande]);
            Commande::creerSuivi($numero_commande, Commande::COMMANDE_STATUS_EXPEDIE, $pdo);
            return new Resultat(true, "La commande a bien été expédiée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de l'expédition de la commande.");
        }
    }

    public static function livrerCommande(int $numero_commande, int $pret_materiel, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Statut = ?, Pret_materiel = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);

        try {
            $newStatut = Commande::COMMANDE_STATUS_TERMINE;
            if ($pret_materiel == 1) {
                $newStatut = Commande::COMMANDE_STATUS_ATTENTE_RETOUR;
            }
            $stmt->execute(params: [$newStatut, $pret_materiel, $numero_commande]);
            Commande::creerSuivi($numero_commande, $newStatut, $pdo);
            return new Resultat(true, "La commande a bien été livrée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la livraison de la commande.");
        }
    }
    public static function terminerCommande(int $numero_commande, int $pret_materiel, PDO $pdo): Resultat
    {
        $sql = "UPDATE Commande SET Statut = ?, Restitution_materiel = ? WHERE Numero_commande = ?";
        $stmt = $pdo->prepare(query: $sql);
        try {
            $restitution_materiel = 0;
            if ($pret_materiel == 1) {
                $restitution_materiel = 1;
            }
            $stmt->execute(params: [Commande::COMMANDE_STATUS_TERMINE, $restitution_materiel, $numero_commande]);
            Commande::creerSuivi($numero_commande, Commande::COMMANDE_STATUS_TERMINE, $pdo);
            return new Resultat(true, "La commande a bien été terminée.");
        } catch (PDOException $e) {
            return new Resultat(false, "Une erreur s'est produite lors de la terminaison de la commande.");
        }
    }
    public function getNumeroCommande(): int
    {
        return $this->numero_commande;
    }

    public function getNombrePersonne(): int
    {
        return $this->nombre_personne;
    }

    public function getDateHeureLivraison(): string
    {
        return $this->date_heure_livraison;
    }
    public function getDateHeureLivraisonInput(): string
    {
        return $this->date_heure_livraison;
    }
    public function getAdresseLivraison(): string
    {
        return $this->adresse_livraison;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }
    public function getMenuNom(): string
    {
        return $this->menu->getNom();
    }
    public function getPrixTotale(): string
    {
        return $this->prix_totale;
    }

    public function getPrixLivraison(): float
    {
        return $this->prix_livraison;
    }

    public function getPrixDistanceLivraison(): float
    {
        return $this->prix_distance_livraison;
    }

    public function getPret_materiel(): bool
    {
        return $this->pret_materiel;
    }
    public function getRestitution_materiel(): bool
    {
        return $this->restitution_materiel;
    }

    public function getEntree(): Produit
    {
        return $this->entree;
    }

    public function getPlat(): Produit
    {
        return $this->plat;
    }

    public function getDessert(): Produit
    {
        return $this->dessert;
    }

    public function isCommande(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUT_COMMANDE);
    }

    public function isAnnule(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_ANNULE);
    }

    public function isValide(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_VALIDE);
    }

    public function isEnPreparation(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_PREPARATION);
    }

    public function isExpedie(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_EXPEDIE);
    }

    public function isLivre(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_LIVRE);
    }

    public function isAttenteRetour(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_ATTENTE_RETOUR);
    }

    public function isTermine(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_TERMINE);
    }


    public static function loadLastCommandeOfUser(int $utilisateur_id, PDO $pdo): int
    {
        $sql = "SELECT Numero_commande FROM commande WHERE Utilisateur_Id = ? ORDER BY Numero_commande DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(params: [$utilisateur_id]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return $resultat["Numero_commande"];
        }
        return 0;
    }
}

?>