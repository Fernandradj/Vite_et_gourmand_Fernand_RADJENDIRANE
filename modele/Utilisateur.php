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
    

    public function __construct(){

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $newId)
    {
        $this->id = $newId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function setUsername(string $newUsername)
    {
        $this->username = $newUsername;
    }
    public function getUserRole(): string
    {
        return $this->role;
    }
    public function setUserRole(string $newUserRole)
    {
        $this->role = $newUserRole;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function setFirstName(
        string
        $newFirstName
    ) {
        $this->firstName = $newFirstName;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function setLastName(
        string
        $newLastName
    ) {
        $this->lastName = $newLastName;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
    public function setFullName(
        string
        $newFullName
    ) {
        $this->fullName = $newFullName;
    }


    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }
     public function setDateofBirth(
        string
        $newDateofBirth
    ) {
        $this->dateOfBirth = $newDateofBirth;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
     public function setEmail(
        string
        $newEmail
    ) {
        $this->email = $newEmail;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
    public function setAddresse(
        string
        $newAddresse
    ) {
        $this->address = $newAddresse;
    }

    public function getTelephone(): string
    {
        return $this->phone;
    }
    public function setTelephone(
        string
        $newTelephone
    ) {
        $this->phone = $newTelephone;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }
    public function setStatut(
        string
        $newStatut
    ) {
        $this->statut = $newStatut;
    }


    public function getPhotoUrl(): string
    {
        return $this->photoUrl;
    }
    public function setPhotoUrl(
        string
        $newPhotoUrl
    ) {
        $this->photoUrl = $newPhotoUrl;
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

}
?>