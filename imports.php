<?php
// require_once 'config.php';
require ROOT_PATH . 'vendor/autoload.php';
$currentFolder = realpath(dirname(__FILE__));
include_once(ROOT_PATH . "database.php");
include_once(ROOT_PATH . "modele/Resultat.php");
include_once(ROOT_PATH . "modele/Utilisateur.php");
include_once(ROOT_PATH . "modele/UtilisateurDAO.php");
include_once(ROOT_PATH . "modele/Avis.php");
include_once(ROOT_PATH . "modele/AvisDAO.php");
include_once(ROOT_PATH . "modele/Commande.php");
include_once(ROOT_PATH . "modele/CommandeDAO.php");
include_once(ROOT_PATH . "modele/Menu.php");
include_once(ROOT_PATH . "modele/MenuDAO.php");
include_once(ROOT_PATH . "modele/Horaire.php");
include_once(ROOT_PATH . "modele/HoraireDAO.php");
include_once(ROOT_PATH . "modele/Produit.php");
include_once(ROOT_PATH . "modele/ProduitDAO.php");
include_once(ROOT_PATH . "modele/Suivi.php");
include_once(ROOT_PATH . "modele/SuiviDAO.php");

session_start();

?>