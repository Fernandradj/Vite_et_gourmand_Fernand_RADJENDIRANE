<?php

class Avis
{

    public const PARTICIPATION_STATUT_PAYE = 'Payé';
    public const PARTICIPATION_STATUT_ANNULE = 'Annulé';
    public const PARTICIPATION_STATUT_EN_COURS = 'En attente de validation';
    public const PARTICIPATION_STATUT_VALIDE = 'Validé';
    public const PARTICIPATION_STATUT_REJETE = 'Rejeté';

    public const RESULT_AVIS_SAVED = "Votre avis a bien été enregistré.";

    public const RESULT_AVIS_VALIDATED = "Votre avis a bien été validé.";

    public const RESULT_AVIS_REJECTED = "Votre avis a bien été rejeté.";

    private int $avis_id;

    private PDO $pdo;

    private int $note;
    private string $commentaire;
    private string $statut;

    private Utilisateur $soumis_par;

    private Utilisateur $valide_refuse_par;

    private Commande $commande;




    public function __construct(int $avis_id, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->avis_id = $avis_id;
        $sql = "SELECT Avis_Id, Commentaire, Note, Statut, Soumis_par, Valide_refuse_par, Commande FROM avis WHERE Avis_Id =?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$avis_id]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultat) {

            if ($resultat["Commentaire"] == null) {
                $this->commentaire = "";
            }
            else {
                $this->commentaire = $resultat["Commentaire"];
            }
            $this->note = $resultat["Note"];
            $this->statut = $resultat["Statut"];

            $utilisateur_id = $resultat["Soumis_par"];
            $this->soumis_par = new Utilisateur($utilisateur_id, $pdo);
            
            $utilisateur_id = $resultat["Valide_refuse_par"];
            if ($utilisateur_id != null) {
                $this->valide_refuse_par = new Utilisateur($utilisateur_id, $pdo);
            }
            
            $commande_id = $resultat["Commande"];
            $this->commande = new Commande($commande_id, $pdo);
        }
    }

    public static function loadAvisFromId(int $id, PDO $pdo): Avis
    {
        $sql = "SELECT Avis_Id, Commentaire, Note, Statut, Credit, Covoiturage_Id, Utilisateur_Id FROM participation WHERE Avis_Id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return new Avis($resultat['Utilisateur_Id'], $resultat['Covoiturage_Id'], $pdo);
        }
        return null;
    }

    public static function loadBestAvis(PDO $pdo): array {
        // SQL : SELECT Avis_Id, Note, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = 'Validé' ORDER BY Note DESC LIMIT 3
        $sql = "SELECT Avis_Id, Note, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = 'Validé' ORDER BY Note DESC LIMIT 3";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([]);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($resultat);
        $avis = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_avis = new Avis($value["Avis_Id"], $pdo);
                array_push($avis,$new_avis);
            }
        }
        return $avis;
    }

    public function soumettreAvis(int $note, string $commentaire, $pdo): Result
    {
        try {
            $sql = "UPDATE participation SET Commentaire = ?, Note = ?, Statut = ? WHERE Avis_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$commentaire, $note, Avis::PARTICIPATION_STATUT_EN_COURS, $this->getId()]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Result(true, Avis::RESULT_AVIS_SAVED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Result(false, Voyage::RESULT_FAIL);
    }

    public function validerAvis(int $employeId, $pdo): Result
    {
        try {
            $sql = "UPDATE participation SET Statut = ?, Employe_Id = ? WHERE Avis_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([Avis::PARTICIPATION_STATUT_VALIDE, $employeId, $this->getId()]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultat) {
                return new Result(true, Avis::RESULT_AVIS_VALIDATED);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Result(false, Voyage::RESULT_FAIL);
    }

    public function rejeterAvis(int $employeId, $pdo): Result
    {
        try {
            $sql = "UPDATE participation SET Statut = ?, Employe_Id = ? WHERE Avis_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([Avis::PARTICIPATION_STATUT_REJETE, $employeId, $this->getId()]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultat) {
                return new Result(true, Avis::RESULT_AVIS_REJECTED);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Result(false, Voyage::RESULT_FAIL);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    public function getCommande() : Commande {

        return $this->commande;
    }

    // public function setComments(String $newComment): void {
    //     if ($newComment == null) {
    //         return;
    //     }
    //     if ($newComment.length() < 50) {
    //         return;
    //     }
    //     $this->commentaire = $newComment;
    // }




    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getSoumisPar(): Utilisateur
    {
        return $this->soumis_par;
    }
    
    public function getSoumisParId(): int
    {
        return $this->soumis_par->getId();
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function getVoyage(): Voyage
    {
        return $this->voyage;
    }

    public function isAvisPaye(): bool
    {
        return ($this->getStatut() == Avis::PARTICIPATION_STATUT_PAYE);
    }

    public function isAvisEnCours(): bool
    {
        return ($this->getStatut() == Avis::PARTICIPATION_STATUT_EN_COURS);
    }

    public function isAvisEnAnnule(): bool
    {
        return ($this->getStatut() == Avis::PARTICIPATION_STATUT_ANNULE);
    }

    public function isAvisValide(): bool
    {
        return ($this->getStatut() == Avis::PARTICIPATION_STATUT_VALIDE);
    }

    public function isAvisRejete(): bool
    {
        return ($this->getStatut() == Avis::PARTICIPATION_STATUT_REJETE);
    }

    public function isAvisNotOpen(): bool
    {
        return ($this->getVoyage()->isVoyageOuvert() || ($this->getVoyage()->isVoyageEnCours()));
    }

    public function isAvisOpen(): bool
    {
        return (($this->getVoyage()->isVoyageTermine()) && $this->isAvisPaye());
    }

    public function isAvisClosed(): bool
    {
        return (!($this->isAvisNotOpen() || $this->isAvisOpen()));
    }
}

?>