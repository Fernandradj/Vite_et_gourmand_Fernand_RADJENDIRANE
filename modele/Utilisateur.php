<?php

class Utilisateur
{
    public const USER_STATUT_ACTIF = 'Actif';

    public const USER_STATUT_SUSPENDU = 'Suspendu';

    public const USER_STATUT_INACTIF = 'Inactif';

    public const USER_ROLE_ADMIN = 'Administrateur';
    public const USER_ROLE_EMPLOYE = 'Employé';
    public const USER_ROLE_UITILISATEUR = 'Client';
    public const RESULT_UPDATE_PROFIL_SUCCESS = "Le profil a été mis à jour avec succès.";
    public const RESULT_UPDATE_NOTE_SUCCESS = "La note a été mis à jour avec succès.";
    public const RESULT_FAIL = "Une erreur s'est produite lors de l'enregistrement. Veuilllez réessayer ultérieurement.";

    private $pdo;
    private int $id;
    private string $username;
    private string $firstName;
    private string $lastName;
    private string $fullName;

    private string $dateOfBirth;

    private string $role;

    private string $statut;

    private string $address;
    private string $phone;
    private string $email;
    // private float $reviewScore;
    // private float $reviewCount;
    private string $photoUrl;
    // private bool $preferenceAnimal;
    // private bool $preferenceFumeur;
    // private string $preference;

    // private float $credit;

    public function __construct(bool $loadData = false, int $id = 0, ?PDO $pdo = null)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        if ($loadData && $id != 0) {
            $sql = "SELECT Utilisateur_Id, Pseudo, Statut, Utilisateur_id, Nom, Prenom, Date_naissance, Adresse, Telephone, Email, Photo, Role FROM utilisateur WHERE Utilisateur_Id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            if ($user) {
                $this->username = $user["Pseudo"];
                $this->firstName = $user["Prenom"];
                $this->lastName = $user["Nom"];
                $this->fullName = $user["Prenom"] . " " . $user["Nom"];
                $this->role = $user["Role"];

                if ($user["Date_naissance"] == null) {
                    $user["Date_naissance"] = "";
                }
                $this->dateOfBirth = $user["Date_naissance"];
                $this->address = $user["Adresse"];
                $this->statut = $user["Statut"];
                $this->phone = $user["Telephone"];
                $this->email = $user["Email"];
                // $this->reviewCount = 0;
                if ($user["Photo"] == null) {
                    $user["Photo"] = "";
                }
                $this->photoUrl = $user["Photo"];
                // $this->preferenceAnimal = $user['Animal_accepte'];
                // $this->preferenceFumeur = $user['Fumeur_accepte'];
                // $this->preference = $user["Autre_preference"];
                // $this->credit = $user["Credit"];

                // $roles = Utilisateur::loadRoles($id, $pdo);
                // $this->role = Utilisateur::checkUserRole($roles);
            }
        }
    }

    public static function loadUserFromUsername(string $username, PDO $pdo): array
    {
        $sql = "SELECT Utilisateur_Id, Pseudo, Password, Statut, Role FROM utilisateur WHERE Pseudo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user) {
            // echo "user found : ".$user['Utilisateur_Id'];
            return $user;
        }
        return [];
    }

    public static function checkEmailAlreadyUsed(string $usermail, PDO $pdo): bool
    {
        $sql = "SELECT Utilisateur_Id FROM utilisateur WHERE Statut = ? AND Email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([Utilisateur::USER_STATUT_ACTIF, $usermail]);
        $user = $stmt->fetch();
        if ($user) {
            return true;
        }
        return false;
    }

    public static function loadUserImageFromId(int $userId, PDO $pdo): array
    {
        $sql = "SELECT Utilisateur_Id, Photo FROM utilisateur WHERE Utilisateur_Id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if ($user) {
            return $user;
        }
        return [];
    }

    // public static function loadRoles(int $userId, PDO $pdo): array
    // {
    //     $sql = "SELECT role.Role_Id, Libelle FROM role JOIN utilisateur_role ON role.Role_Id = utilisateur_role.Role_Id WHERE Utilisateur_Id = ?";
    //     $stmt = $pdo->prepare($sql);
    //     $stmt->execute([$userId]);
    //     $roles = $stmt->fetchAll();
    //     if ($roles) {
    //         return $roles;
    //     }
    //     return [];
    // }

    /* public static function checkUserRole(array $roles): string
    {
        $userRole = "";
        $userIsChauffeur = false;
        $userIsPassager = false;
        $userIsEmployee = false;
        $userIsAdmin = false;
        foreach ($roles as $role) {
            // echo "role : " . $role;
            if ($role['Libelle'] == Utilisateur::USER_ROLE_CHAUFFEUR) {
                $userIsChauffeur = true;
            }
            if ($role['Libelle'] == Utilisateur::USER_ROLE_PASSAGER) {
                $userIsPassager = true;
            }
            if ($role['Libelle'] == Utilisateur::USER_ROLE_ADMIN) {
                $userIsAdmin = true;
            }
            if ($role['Libelle'] == Utilisateur::USER_ROLE_EMPLOYE) {
                $userIsEmployee = true;
            }
        }
        if ($userIsAdmin) {
            $userRole = Utilisateur::USER_ROLE_ADMIN;
        } else if ($userIsEmployee) {
            $userRole = Utilisateur::USER_ROLE_EMPLOYE;
        } else if ($userIsChauffeur && $userIsPassager) {
            $userRole = Utilisateur::USER_ROLE_PASSAGER_ET_CHAUFFEUR;
        } else if ($userIsPassager) {
            $userRole = Utilisateur::USER_ROLE_PASSAGER;
        } else if ($userIsChauffeur) {
            $userRole = Utilisateur::USER_ROLE_CHAUFFEUR;
        }
        return $userRole;
    } */

    /* public static function loadAllRoles(PDO $pdo): array
    {
        $sql = "SELECT Role_Id, Libelle FROM role";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([]);
        $roles = $stmt->fetchAll();
        return $roles;
    } */

    /* public static function loadAllRolesMapbyId(PDO $pdo): array
    {
        $allRoles = Utilisateur::loadAllRoles($pdo);
        $roleMap = [];
        foreach ($allRoles as $role) {
            $roleMap[$role['Libelle']] = $role['Role_Id'];
        }
        return $roleMap;
    } */

    /* public static function loadIdsFromRoles(string $userRole, PDO $pdo): array
    {
        if (($userRole == null) || ($userRole == '')) {
            return [];
        }
        $rolesMap = Utilisateur::loadAllRolesMapbyId($pdo);
        $roleIds = [];
        if (str_contains($userRole, Utilisateur::USER_ROLE_PASSAGER)) {
            array_push($roleIds, $rolesMap[Utilisateur::USER_ROLE_PASSAGER]);
        }
        if (str_contains($userRole, Utilisateur::USER_ROLE_CHAUFFEUR)) {
            array_push($roleIds, $rolesMap[Utilisateur::USER_ROLE_CHAUFFEUR]);
        }
        return $roleIds;
    } */

    public function updateUserProfile(string $username, string $lastName, string $firstName, string $address, string $phone, string $email, string $dateOfBirth, string $hashedPassword, string $photo, PDO $pdo): Resultat
    {
        try {
            if (($hashedPassword != null) && ($hashedPassword != '')) {
                if (($photo != null) && ($photo != '')) {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ?, Password = ?, Photo = ? WHERE Utilisateur_Id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $hashedPassword, $photo, $this->getId()]);
                } else {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ?, Password = ? WHERE Utilisateur_Id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $hashedPassword, $this->getId()]);
                }
            } else {
                if (($photo != null) && ($photo != '')) {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ?, Photo = ? WHERE Utilisateur_Id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $photo, $this->getId()]);
                } else {
                    $sql = "UPDATE utilisateur SET Pseudo = ?, Nom = ?, Prenom = ?, Adresse = ?, Telephone = ?, Email = ?, Date_naissance = ? WHERE Utilisateur_Id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $lastName, $firstName, $address, $phone, $email, $dateOfBirth, $this->getId()]);
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

    /* public function loadVoitures(PDO $pdo): array
    {
        $sql_voitures = "SELECT Voiture_Id, Marque, Modele, Immatriculation, Date_premiere_immatriculation, Couleur, Energie, Nb_place FROM voiture WHERE Utilisateur_Id = ?";
        $stmt_voitures = $pdo->prepare($sql_voitures);
        $stmt_voitures->execute([$this->getId()]);
        $voitures = $stmt_voitures->fetchAll(PDO::FETCH_ASSOC);
        if ($voitures) {
            if (count($voitures) > 0) {
                return $voitures;
            }
        }
        return [];
    } */

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserRole(): string
    {
        return $this->role;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getTelephone(): string
    {
        return $this->phone;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }

    public function userIsAdmin(): bool
    {
        return ($this->role == Utilisateur::USER_ROLE_ADMIN);
    }

    public function userIsEmploye(): bool
    {
        return (($this->role == Utilisateur::USER_ROLE_EMPLOYE) || ($this->role == Utilisateur::USER_ROLE_ADMIN));
    }

    public function userIsClient(): bool
    {
        return ($this->role == Utilisateur::USER_ROLE_UITILISATEUR);
    }

    public function isActif(): bool
    {
        return ($this->statut == Utilisateur::USER_STATUT_ACTIF);
    }

    public static function loadActiveUsers(PDO $pdo): array
    {
        $sql = "SELECT Utilisateur_Id, Nom, Prenom, Pseudo, Email, Telephone, Role FROM utilisateur WHERE Statut = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([Utilisateur::USER_STATUT_ACTIF]);
        $resultats = $stmt->fetchAll();
        $users = [];
        if ($resultats) {
            foreach ($resultats as $key => $user) {
                array_push($users, new Utilisateur(true, $user['Utilisateur_Id'], $pdo));
            }
        }
        return $users;
    }
    
    public static function loadAllUsers(PDO $pdo): array
    {
        $sql = "SELECT Utilisateur_Id, Nom, Prenom, Pseudo, Email, Telephone, Role FROM utilisateur";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([]);
        $resultats = $stmt->fetchAll();
        $users = [];
        if ($resultats) {
            foreach ($resultats as $key => $user) {
                array_push($users, new Utilisateur(true, $user['Utilisateur_Id'], $pdo));
            }
        }
        return $users;
    }

    public static function suspendreUtilisateurs(array $userIds, PDO $pdo): Resultat
    {
        try {
            $sql = "UPDATE utilisateur SET Statut = 'Suspendu' WHERE Utilisateur_Id IN (" . implode(',', array_fill(0, count($userIds), '?')) . ")";
            $stmt = $pdo->prepare($sql);
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

    /* public static function createEmployee(string $username, string $email, PDO $pdo): Resultat
    {
        try {
            $sql = "INSERT INTO utilisateur (Pseudo, Email, Role) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, Utilisateur::USER_ROLE_EMPLOYE]);
            if ($stmt) {
                return new Resultat(true, "Employé créé avec succès.");
            }
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            return new Resultat(false, "Une erreur s'est produite lors de la création de l'employé. Veuillez réessayer ultérieurement.");
        }
        return new Resultat(false, "Une erreur s'est produite lors de la création de l'employé. Veuillez réessayer ultérieurement.");
    } */

    /* public function updateCredit(float $newCredit, PDO $pdo): bool
    {
        $sql = "UPDATE utilisateur SET Credit = " . $newCredit . " WHERE Utilisateur_Id = " . $this->getId();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt) {
            return true;
        }
        return false;
    } */

    /* public function updateNote(float $credit, PDO $pdo): Resultat
    {

        try {
            $sql = "SELECT utilisateur.Utilisateur_id, AVG(participation.note) avgNote FROM `participation` JOIN covoiturage ON covoiturage.Covoiturage_id = participation.Covoiturage_id AND covoiturage.Statut = 'Terminé' JOIN voiture ON voiture.Voiture_id = covoiturage.Voiture_id JOIN utilisateur on voiture.Utilisateur_id = utilisateur.Utilisateur_id WHERE utilisateur.Utilisateur_id = ? AND participation.Statut = 'Validé' GROUP BY utilisateur.Utilisateur_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->getId()]);
            $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultat) {
                $avgNote = $resultat["avgNote"];
                $sql = "UPDATE utilisateur SET Note = ? WHERE Utilisateur_Id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$avgNote, $this->getId()]);
                $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($resultat) {
                    return new Resultat(true, Utilisateur::RESULT_UPDATE_NOTE_SUCCESS);
                }
            }
        } catch (Exception $e) {

        }
        return new Resultat(false, Utilisateur::RESULT_FAIL);
    } */

}
?>