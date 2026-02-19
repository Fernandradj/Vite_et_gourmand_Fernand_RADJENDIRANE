<?php

class Avis
{

    // public const AVIS_STATUT_PAYE = 'Payé';
    // public const AVIS_STATUT_ANNULE = 'Annulé';
    public const AVIS_STATUT_EN_COURS = 'En attente de validation';
    public const AVIS_STATUT_VALIDE = 'Validé';
    public const AVIS_STATUT_REJETE = 'Rejeté';

    public const RESULT_AVIS_SAVED = "Votre avis a bien été enregistré.";

    public const RESULT_AVIS_VALIDATED = "Votre avis a bien été validé.";

    public const RESULT_AVIS_REJECTED = "Votre avis a bien été rejeté.";

    public const RESULT_FAIL = "Une erreur s'est produite lors de l'enregistrement. Veuilllez réessayer ultérieurement.";

    private int $avis_id;

    private PDO $pdo;

    private int $note;
    private string $commentaire;
    private string $statut;

    private Utilisateur $soumis_par;

    private Utilisateur $valide_refuse_par;

    private Commande $commande;




    public function __construct(bool $loadData, int $avis_id, string $statut = "", string $commentaire = "", int $note = 0, ?Utilisateur $soumis_par, ?Utilisateur $valide_refuse_par = null, ?Commande $commande = null, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->avis_id = $avis_id;
        if ($loadData) {
            $sql = "SELECT Avis_Id, Commentaire, Note, Statut, Soumis_par, Valide_refuse_par, Commande FROM avis WHERE Avis_Id =?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$avis_id]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultat) {

                if ($resultat["Commentaire"] == null) {
                    $this->commentaire = "";
                } else {
                    $this->commentaire = $resultat["Commentaire"];
                }
                $this->note = $resultat["Note"];
                $this->statut = $resultat["Statut"];

                $utilisateur_id = $resultat["Soumis_par"];
                $this->soumis_par = new Utilisateur(true, $utilisateur_id, $pdo);

                $utilisateur_id = $resultat["Valide_refuse_par"];
                if ($utilisateur_id != null) {
                    $this->valide_refuse_par = new Utilisateur(true, $utilisateur_id, $pdo);
                }

                $commande_id = $resultat["Commande"];
                $this->commande = new Commande($commande_id, $pdo);
            }
        } else {
            $this->statut = $statut;
            $this->commentaire = $commentaire;
            $this->note = $note;
            if ($soumis_par == null) {
                $this->soumis_par = new Utilisateur(true, 0, $pdo);
            } else {
                $this->soumis_par = $soumis_par;
            }
            if ($valide_refuse_par == null) {
                $this->valide_refuse_par = new Utilisateur(true, 0, $pdo);
            } else {
                $this->valide_refuse_par = $valide_refuse_par;
            }
            if ($commande == null) {
                $this->commande = new Commande(0, $pdo);
            } else {
                $this->commande = $commande;
            }
        }
    }

    /* public static function loadAvisFromId(int $id, PDO $pdo): Avis
    {
        $sql = "SELECT Avis_Id, Commentaire, Note, Statut, Soumis_par, Valide_refuse_par, Commande FROM avis WHERE Avis_Id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return new Avis(false, $resultat['Avis_Id'], $resultat['Statut'], $resultat['Commentaire'], $resultat['Note'], new Utilisateur(true, $resultat['Soumis_par'], $pdo), new Utilisateur(true, $resultat['Valide_refuse_par'], $pdo), new Commande($resultat['Commande'], $pdo), $pdo);
        }
        return null;
    } */

    public static function loadAvisIdOfCommande(int $commandeId, PDO $pdo): int
    {
        $sql = "SELECT Avis_Id FROM avis WHERE Commande = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$commandeId]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return $resultat['Avis_Id'];
        }
        return 0;
    }

    public static function loadBestAvis(PDO $pdo): array
    {
        // SQL : SELECT Avis_Id, Note, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = 'Validé' ORDER BY Note DESC LIMIT 3
        $sql = "SELECT Avis_Id, Note, avis.Statut avisStatut, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo, Valide_refuse_par, Commande FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = ? ORDER BY Note DESC LIMIT 3";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([Avis::AVIS_STATUT_VALIDE]);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($resultat);
        $avis = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_avis = new Avis(false, $value["Avis_Id"], $value["avisStatut"], $value["Commentaire"], $value["Note"], new Utilisateur(true, $value["Soumis_par"], $pdo), new Utilisateur(true, $value["Valide_refuse_par"], $pdo), new Commande($value["Commande"], $pdo), $pdo);
                array_push($avis, $new_avis);
            }
        }
        return $avis;
    }

    public static function loadAvisAValider(PDO $pdo): array
    {
        // SQL : SELECT Avis_Id, Note, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = 'Validé' ORDER BY Note DESC LIMIT 3
        $sql = "SELECT Avis_Id, Note, avis.Statut avisStatut, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo, Valide_refuse_par, Commande FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = ? ORDER BY Avis_Id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([Avis::AVIS_STATUT_EN_COURS]);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($resultat);
        $avis = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $new_avis = new Avis(false, $value["Avis_Id"], $value["avisStatut"], $value["Commentaire"], $value["Note"], new Utilisateur(true, $value["Soumis_par"], $pdo), new Utilisateur(true, 0, $pdo), new Commande($value["Commande"], $pdo), $pdo);
                array_push($avis, $new_avis);
            }
        }
        return $avis;
    }

    public static function soumettreAvis(int $commandeId, int $note, string $commentaire, int $utilisateurId, $pdo): Resultat
    {
        try {
            $sql = "INSERT INTO avis (Commentaire, Note, Statut, Commande, Soumis_par) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$commentaire, $note, Avis::AVIS_STATUT_EN_COURS, $commandeId, $utilisateurId]);
            return new Resultat(true, Avis::RESULT_AVIS_SAVED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }

    public static function validerAvis(int $avisId, int $employeId, $pdo): Resultat
    {
        try {
            $sql = "UPDATE avis SET Statut = ?, Valide_refuse_par = ? WHERE Avis_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([Avis::AVIS_STATUT_VALIDE, $employeId, $avisId]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Resultat(true, Avis::RESULT_AVIS_VALIDATED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }

    public static function rejeterAvis(int $avisId, int $employeId, $pdo): Resultat
    {
        try {
            $sql = "UPDATE avis SET Statut = ?, Valide_refuse_par = ? WHERE Avis_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([Avis::AVIS_STATUT_REJETE, $employeId, $avisId]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Resultat(true, Avis::RESULT_AVIS_REJECTED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }

    public function getId(): int
    {
        return $this->avis_id;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
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

    public function getValideRefusePar(): Utilisateur
    {
        return $this->valide_refuse_par;
    }

    public function getValideRefuseParId(): int
    {
        return $this->valide_refuse_par->getId();
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    // public function isAvisPaye(): bool
    // {
    //     return ($this->getStatut() == Avis::AVIS_STATUT_PAYE);
    // }

    public function isAvisEnCours(): bool
    {
        return ($this->getStatut() == Avis::AVIS_STATUT_EN_COURS);
    }

    // public function isAvisEnAnnule(): bool
    // {
    //     return ($this->getStatut() == Avis::AVIS_STATUT_ANNULE);
    // }

    public function isAvisValide(): bool
    {
        return ($this->getStatut() == Avis::AVIS_STATUT_VALIDE);
    }

    public function isAvisRejete(): bool
    {
        return ($this->getStatut() == Avis::AVIS_STATUT_REJETE);
    }

    public function isAvisOpen(): bool
    {
        return ($this->isAvisEnCours());
    }

    public function isAvisClosed(): bool
    {
        return (!$this->isAvisOpen());
    }
}

?>