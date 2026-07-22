<?php
class UtilisateurDAO
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById(bool $loadData = false, int $id = 0): Utilisateur
    {
        $userData = new Utilisateur();

        $userData->setId($id);
        if ($loadData && $id != 0) {
            $sql = "SELECT Utilisateur_Id, Pseudo, Statut, Utilisateur_id, Nom, Prenom, Date_naissance, Adresse, Telephone, Email, Photo, Role FROM utilisateur WHERE Utilisateur_Id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            if ($user) {
                $userData->setUsername($user["Pseudo"]);
                $userData->setFirstName($user["Prenom"]);
                $userData->setLastName($user["Nom"]);
                $userData->setFullName($user["Prenom"] . " " . $user["Nom"]);
                $userData->setUserRole($user["Role"]);

                if ($user["Date_naissance"] == null) {
                    $user["Date_naissance"] = "";
                }
                $userData->setDateofBirth($user["Date_naissance"]);
                $userData->setAddresse($user["Adresse"]);
                $userData->setStatut($user["Statut"]);
                $userData->setTelephone($user["Telephone"]);
                $userData->setEmail($user["Email"]);
                // $userData->reviewCount = 0;
                if ($user["Photo"] == null) {
                    $user["Photo"] = "";
                }
                $userData->setPhotoUrl($user["Photo"]);
                // $userData->preferenceAnimal = $user['Animal_accepte'];
                // $userData->preferenceFumeur = $user['Fumeur_accepte'];
                // $userData->preference = $user["Autre_preference"];
                // $userData->credit = $user["Credit"];

                // $roles = Utilisateur::loadRoles($id, $pdo);
                // $userData->role = Utilisateur::checkUserRole($roles);
            }
        }
        return $userData;
    }

    public function loadUserFromUsername(string $username): array
    {
        $sql = "SELECT Utilisateur_Id, Pseudo, Password, Statut, Role FROM utilisateur WHERE Pseudo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user) {
            // echo "user found : ".$user['Utilisateur_Id'];
            return $user;
        }
        return [];
    }

    public function checkEmailAlreadyUsed(string $usermail): bool
    {
        $sql = "SELECT Utilisateur_Id FROM utilisateur WHERE Statut = ? AND Email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([Utilisateur::USER_STATUT_ACTIF, $usermail]);
        $user = $stmt->fetch();
        if ($user) {
            return true;
        }
        return false;
    }

    public function loadUserImageFromId(int $userId): array
    {
        $sql = "SELECT Utilisateur_Id, Photo FROM utilisateur WHERE Utilisateur_Id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if ($user) {
            return $user;
        }
        return [];
    }



    public function updateUserProfile(int $id, string $username, string $lastName, string $firstName, string $address, string $phone, string $email, string $dateOfBirth, string $hashedPassword, string $photo): Resultat
    {
        try {
            if (($hashedPassword != null) && ($hashedPassword != '')) {
                if (($photo != null) && ($photo != '')) {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ?, Password = ?, Photo = ? WHERE Utilisateur_Id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $hashedPassword, $photo, $id]);
                } else {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ?, Password = ? WHERE Utilisateur_Id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $hashedPassword, $id]);
                }
            } else {
                if (($photo != null) && ($photo != '')) {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ?, Photo = ? WHERE Utilisateur_Id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $photo, $id]);
                } else {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ? WHERE Utilisateur_Id = ?";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $id]);
                }
            }
            if ($stmt) {
                return new Resultat(true, Utilisateur::RESULT_UPDATE_PROFIL_SUCCESS);
            }
        } catch (Exception $e) {
            echo $e;
            return new Resultat(false, Utilisateur::RESULT_FAIL);
        }
        return new Resultat(false, Utilisateur::RESULT_FAIL);
    }


    public function loadActiveUsers(): array
    {
        $sql = "SELECT Utilisateur_Id, Nom, Prenom, Pseudo, Email, Telephone, Role FROM utilisateur WHERE Statut = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([Utilisateur::USER_STATUT_ACTIF]);
        $resultats = $stmt->fetchAll();
        $users = [];
        if ($resultats) {
            foreach ($resultats as $key => $user) {
                array_push($users, $this->getById(true, $user['Utilisateur_Id']));
            }
        }
        return $users;
    }

    public function loadAllUsers(): array
    {
        $sql = "SELECT Utilisateur_Id, Nom, Prenom, Pseudo, Email, Telephone, Role FROM utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([]);
        $resultats = $stmt->fetchAll();
        $users = [];
        if ($resultats) {
            foreach ($resultats as $key => $user) {
                array_push($users, $this->getById(true, $user['Utilisateur_Id']));
            }
        }
        return $users;
    }

    public function suspendreUtilisateurs(array $userIds): Resultat
    {
        try {
            $sql = "UPDATE utilisateur SET Statut = 'Suspendu' WHERE Utilisateur_Id IN (" . implode(',', array_fill(0, count($userIds), '?')) . ")";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($userIds);
            if ($stmt) {
                return new Resultat(true, "Les utilisateurs ont été suspendus avec succès.");
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            return new Resultat(false, "Une erreur s'est produite lors de la suspension de l'utilisateur.");
        }
        return new Resultat(false, "Une erreur s'est produite lors de la suspension de l'utilisateur.");
    }


}
?>