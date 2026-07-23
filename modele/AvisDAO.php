<?php
class AvisDAO
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById(bool $loadData, int $avis_id, string $statut, string $commentaire, int $note, ?Utilisateur $soumis_par, ?Utilisateur $valide_refuse_par = null, ?Commande $commande = null): Avis
    {

        $avisData = new Avis();
        $avisData->setId($avis_id);
        if ($loadData) {
            $sql = "SELECT Avis_Id, Commentaire, Note, Statut, Soumis_par, Valide_refuse_par, Commande FROM avis WHERE Avis_Id =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$avis_id]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultat) {

                if ($resultat["Commentaire"] == null) {
                    $avisData->setCommentaire("");
                } else {
                    $avisData->setcommentaire($resultat["Commentaire"]);
                }
                $avisData->setNote($resultat["Note"]);
                $avisData->setStatut($resultat["Statut"]);

                $utilisateur_id = $resultat["Soumis_par"];
                $userDAO = new UtilisateurDAO($this->pdo);
                $avisData->setSoumisPar($userDAO->getById(true, $utilisateur_id));

                $utilisateur_id = $resultat["Valide_refuse_par"];
                if ($utilisateur_id != null) {
                    $userDAO = new UtilisateurDAO($this->pdo);
                    $avisData->setValideRefusePar($userDAO->getById(true, $utilisateur_id));
                }

                $commande_id = $resultat["Commande"];
                $commandeDAO = new CommandeDAO($this->pdo);
                $avisData->setCommande($commandeDAO->getById($commande_id));
            }
        } else {
            $avisData->setStatut($statut);
            $avisData->setCommentaire($commentaire);
            $avisData->setNote($note);
            if ($soumis_par == null) {
                $userDAO = new UtilisateurDAO($this->pdo);
                $avisData->setSoumisPar($userDAO->getById(true, 0));
            } else {
                $avisData->setSoumisPar($soumis_par);
            }
            if ($valide_refuse_par == null) {
                $userDAO = new UtilisateurDAO($this->pdo);
                $avisData->setValideRefusePar($userDAO->getById(true, 0));
            } else {
                $avisData->setValideRefusePar($valide_refuse_par);
            }
            if ($commande == null) {
                $commandeDAO = new CommandeDAO($this->pdo);
                $avisData->setCommande($commandeDAO->getById(0));
            } else {
                $avisData->setCommande($commande);
            }
        }
        return $avisData;
    }



    public function loadAvisIdOfCommande(int $commandeId): int
    {
        $sql = "SELECT Avis_Id FROM avis WHERE Commande = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$commandeId]);
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return $resultat['Avis_Id'];
        }
        return 0;
    }

    public function loadBestAvis(): array
    {
        // SQL : SELECT Avis_Id, Note, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = 'Validé' ORDER BY Note DESC LIMIT 3
        $sql = "SELECT Avis_Id, Note, avis.Statut avisStatut, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo, Valide_refuse_par, Commande FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = ? ORDER BY Note DESC LIMIT 3";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([Avis::AVIS_STATUT_VALIDE]);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($resultat);
        $avis = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $userDAO = new UtilisateurDAO($this->pdo);
                $commandeDAO = new CommandeDAO($this->pdo);
                $new_avis = $this->getById(false, $value["Avis_Id"], $value["avisStatut"], $value["Commentaire"], $value["Note"], $userDAO->getById(true, $value["Soumis_par"]), $userDAO->getById(true, $value["Valide_refuse_par"]), $commandeDAO->getById($value["Commande"]));
                array_push($avis, $new_avis);
            }
        }
        return $avis;
    }

    public function loadAvisAValider(): array
    {
        // SQL : SELECT Avis_Id, Note, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = 'Validé' ORDER BY Note DESC LIMIT 3
        $sql = "SELECT Avis_Id, Note, avis.Statut avisStatut, Commentaire, Soumis_par, utilisateur.Nom, utilisateur.Prenom, utilisateur.Pseudo, utilisateur.Photo, Valide_refuse_par, Commande FROM avis JOIN utilisateur ON avis.Soumis_par = utilisateur.Utilisateur_Id WHERE avis.Statut = ? ORDER BY Avis_Id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([Avis::AVIS_STATUT_EN_COURS]);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($resultat);
        $avis = [];
        if ($resultat) {
            foreach ($resultat as $value) {
                $userDAO = new UtilisateurDAO($this->pdo);
                $commandeDAO = new CommandeDAO($this->pdo);

                $new_avis = $this->getById(false, $value["Avis_Id"], $value["avisStatut"], $value["Commentaire"], $value["Note"], $userDAO->getById(true, $value["Soumis_par"]), $userDAO->getById(true), $commandeDAO->getById($value["Commande"]));
                array_push($avis, $new_avis);
            }
        }
        return $avis;
    }

    public function soumettreAvis(int $commandeId, int $note, string $commentaire, int $utilisateurId): Resultat
    {
        try {
            $sql = "INSERT INTO avis (Commentaire, Note, Statut, Commande, Soumis_par) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$commentaire, $note, Avis::AVIS_STATUT_EN_COURS, $commandeId, $utilisateurId]);
            return new Resultat(true, Avis::RESULT_AVIS_SAVED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }

    public function validerAvis(int $avisId, int $employeId): Resultat
    {
        try {
            $sql = "UPDATE avis SET Statut = ?, Valide_refuse_par = ? WHERE Avis_Id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([Avis::AVIS_STATUT_VALIDE, $employeId, $avisId]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Resultat(true, Avis::RESULT_AVIS_VALIDATED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }

    public function rejeterAvis(int $avisId, int $employeId): Resultat
    {
        try {
            $sql = "UPDATE avis SET Statut = ?, Valide_refuse_par = ? WHERE Avis_Id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([Avis::AVIS_STATUT_REJETE, $employeId, $avisId]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
            return new Resultat(true, Avis::RESULT_AVIS_REJECTED);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return new Resultat(false, Avis::RESULT_FAIL);
    }


} ?>