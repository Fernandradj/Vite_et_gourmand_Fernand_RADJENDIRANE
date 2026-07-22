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

    private int $note;
    private string $commentaire;
    private string $statut;

    private Utilisateur $soumis_par;

    private Utilisateur $valide_refuse_par;

    private Commande $commande;

    public function getId(): int
    {
        return $this->avis_id;
    }
    public function setId(int $newId)
    {
        $this->avis_id = $newId;
    }

    public function getNote(): int
    {
        return $this->note;
    }
    
    public function setNote(int $newNote)
    {
        $this->note = $newNote;
    }

    public function getCommentaire(): string
    {
        return $this->commentaire;
    }
    public function setCommentaire(string $newCommentaire)
    {
        $this->commentaire = $newCommentaire;

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
    public function setStatut(string $newStatut)
    {
        $this->statut = $newStatut;
    }

    public function getSoumisPar(): Utilisateur
    {
        return $this->soumis_par;
    }
    public function setSoumisPar(Utilisateur $newSoumisPar)
    {
        $this->soumis_par = $newSoumisPar;
    }
    public function getSoumisParId(): int
    {
        return $this->soumis_par->getId();
    }
    public function setSoumisParId(int $newSoumisParId)
    {
        $this->soumis_par->setId($newSoumisParId);
    }

    public function getValideRefusePar(): Utilisateur
    {
        return $this->valide_refuse_par;
    }
    public function setValideRefusePar(Utilisateur $newValideRefusePar)
    {
        $this->valide_refuse_par = $newValideRefusePar;
    }

    public function getValideRefuseParId(): int
    {
        return $this->valide_refuse_par->getId();
    }
    public function setValideRefuseParId(int $newValideRefuseParId)
    {
        $this->valide_refuse_par->setId($newValideRefuseParId);
    }
    public function getCommande(): Commande
    {
        return $this->commande;
    }
    public function setCommande($newCommande)
    {
        $this->commande = $newCommande;
    }

    // public function isAvisPaye(): bool
    // {
    //     return ($this->getStatut() == Avis::AVIS_STATUT_PAYE);
    // }

    public function isAvisEnCours(): bool
    {
        return ($this->getStatut() == Avis::AVIS_STATUT_EN_COURS);
    }
    // public function set isAvisEnCours(bool $newisAvisEnCours) {
//     $this->isAvisEnCours = $newisAvisEnCours;
// }
    // public function isAvisEnAnnule(): bool
    // {
    //     return ($this->getStatut() == Avis::AVIS_STATUT_ANNULE);
    // }

    public function isAvisValide(): bool
    {
        return ($this->getStatut() == Avis::AVIS_STATUT_VALIDE);
    }
    // public function setisAvisValide(bool $newisAvisValide){
    //     $this->isAvisValide = $newisAvisValide;
    // }

    public function isAvisRejete(): bool
    {
        return ($this->getStatut() == Avis::AVIS_STATUT_REJETE);
    }
    //     public function setisAvisRejete(bool $newisAvisRejete)
// {$this->isAvisRejete = $newisAvisRejete;
// }
    public function isAvisOpen(): bool
    {
        return ($this->isAvisEnCours());
    }
    // public function setisAvisOpen(bool $newisAvisOpen){
    //     $this->isAvisOpen = $newisAvisOpen;
    // }

    public function isAvisClosed(): bool
    {
        return (!$this->isAvisOpen());
    }
    // public function setisAvisClosed(bool $newAvisClosed){
    //     $this->isAvisClosed = $newAvisClosed;
    // }

}

?>