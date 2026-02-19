<?php

$currentFolder = realpath(dirname(__FILE__));
include_once("database.php");
include_once($currentFolder."/modele/Resultat.php");
include_once($currentFolder."/modele/Utilisateur.php");
include_once($currentFolder."/modele/Avis.php");
include_once($currentFolder."/modele/Commande.php");
include_once($currentFolder."/modele/Menu.php");
include_once($currentFolder."/modele/Horaire.php");
include_once($currentFolder."/modele/Produit.php");
include_once($currentFolder."/modele/Suivi.php");
session_start();


?>