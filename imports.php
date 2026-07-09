<?php
require './vendor/autoload.php';
$currentFolder = realpath(dirname(__FILE__));
include_once("database.php");
include_once($currentFolder."/modele/Resultat.php");
include_once($currentFolder."/modele/Utilisateur.php");
include_once($currentFolder."/modele/Avis.php");
include_once($currentFolder."/modele/Commande.php");
include_once($currentFolder."/modele/CommandeDAO.php");
include_once($currentFolder."/modele/Menu.php");
include_once($currentFolder."/modele/MenuDAO.php");
include_once($currentFolder."/modele/Horaire.php");
include_once($currentFolder."/modele/HoraireDAO.php");
include_once($currentFolder."/modele/Produit.php");
include_once($currentFolder."/modele/ProduitDAO.php");
include_once($currentFolder."/modele/Suivi.php");
include_once($currentFolder."/modele/UtilisateurDAO.php");
include_once($currentFolder."/modele/AvisDAO.php");

session_start();

?>