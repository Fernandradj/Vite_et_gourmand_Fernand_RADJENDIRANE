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

    public const DATA_TYPE_PRIX = "prix";
    public const DATA_TYPE_NB_CMD = "nbCommande";

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

    public function getMenu(): Menu
    {
        return $this->menu;
    }
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function getSuivis(): array
    {
        return $this->suivis;
    }

    public function setSuivis(array $suivis)
    {
        $this->suivis = $suivis;
    }

    public function getNumeroCommande(): int
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande(int $numeroCommande)
    {
        $this->numero_commande = $numeroCommande;
    }


    public function getNombrePersonne(): int
    {
        return $this->nombre_personne;
    }
    public function setNombrePersonne(int $nombrePersonne)
    {
        $this->nombre_personne = $nombrePersonne;
    }

    public function getDateCommande(): string
    {
        return $this->date_commande;
    }
    public function setDateCommande(string $dateCommande)
    {
        $this->date_commande = $dateCommande;
    }

    public function getDateHeureLivraison(): string
    {
        return $this->date_heure_livraison;
    }
    public function setDateHeureLivraison(string $dateHeureLivraison)
    {
        $this->date_heure_livraison = $dateHeureLivraison;
    }
    public function getDateHeureLivraisonInput(): string
    {
        return $this->date_heure_livraison;
    }

    public function getAdresseLivraison(): string
    {
        return $this->adresse_livraison;
    }
    public function setAdresseLivraison(string $adresseLivraison)
    {
        $this->adresse_livraison = $adresseLivraison;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }
    public function setStatut(string $statut)
    {
        $this->statut = $statut;
    }
    public function getMenuNom(): string
    {
        return $this->menu->getNom();
    }
    public function getPrixTotale(): string
    {
        return $this->prix_totale;
    }
    public function setPrixTotale(float $prixTotale)
    {
        $this->prix_totale = $prixTotale;
    }

    public function getPrixCommande(): float
    {
        return $this->prix_commande;
    }
    public function setPrixCommande(float $prixCommande)
    {
        $this->prix_commande = $prixCommande;
    }

    public function getReduction(): string
    {
        return $this->reduction;
    }
    public function setReduction(float $reduction)
    {
        $this->reduction = $reduction;
    }


    public function getPrixLivraison(): float
    {
        return $this->prix_livraison;
    }
    public function setPrixLivraison(float $prixLivraison)
    {
        $this->prix_livraison = $prixLivraison;
    }

    public function getPrixDistanceLivraison(): float
    {
        return $this->prix_distance_livraison;
    }
    public function setPrixDistanceLivraison(float $prixDistanceLivraison)
    {
        $this->prix_distance_livraison = $prixDistanceLivraison;
    }

    public function getPret_materiel(): bool
    {
        return $this->pret_materiel;
    }
    public function setPret_materiel(bool $pretMateriel)
    {
        $this->pret_materiel = $pretMateriel;
    }
    public function getRestitution_materiel(): bool
    {
        return $this->restitution_materiel;
    }
    public function setRestitution_materiel(bool $restitutionMateriel)
    {
        $this->restitution_materiel = $restitutionMateriel;
    }

    public function getEntree(): Produit
    {
        return $this->entree;
    }
    public function setEntree(Produit $entree)
    {
        $this->entree = $entree;
    }

    public function getPlat(): Produit
    {
        return $this->plat;
    }
    public function setPlat(Produit $plat)
    {
        $this->plat = $plat;
    }

    public function getDessert(): Produit
    {
        return $this->dessert;
    }
    public function setDessert(Produit $dessert)
    {
        $this->dessert = $dessert;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $user)
    {
        $this->utilisateur = $user;
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

    public function isAttenteRetour(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_ATTENTE_RETOUR);
    }

    public function isTermine(): bool
    {
        return ($this->statut == Commande::COMMANDE_STATUS_TERMINE);
    }

}

?>