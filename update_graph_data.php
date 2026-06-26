<?php
header('Content-Type: application/json');
$currentFolder = realpath(dirname(__FILE__));
include_once("database.php");
include_once($currentFolder."/modele/Commande.php");

$valeur = "";
// On imagine que vous traitez la donnée ici
if (isset($_POST['nouvelle_valeur'])) {
    $valeur = $_POST['nouvelle_valeur'];
    if ($valeur == "All") {
        $valeur = "";
    }
    // print_r($valeur);
}

$startDate = "";
if (isset($_POST['nouvelleStartDate'])) {
    $startDate = $_POST ['nouvelleStartDate'];
    // if ($startDate == 'All'){
    //     $startDate = "";
    // }
}
$endDate = "";
if (isset($_POST['nouvelleEndDate'])) {
    $endDate = $_POST ['nouvelleEndDate'];
    // if ($endDate == 'All'){
    //     $endDate = "";
    // }
}

$labels = [];
$values = [];
$cavalues = [];

$data = Commande::loadChiffresMenus($valeur, $startDate, $endDate, $pdo);

foreach ($data as $key => $value) {
    

    $labels[] = $key;
    $values[] = $data[$key]['nbCommande'];
    $cavalues[] = $data[$key]['prix'];
}
// print_r($labels);
// print_r($values);

// Structure de la réponse que JavaScript va recevoir
$reponse = [
    'status' => 'success',
    'nouveauLabel' => $labels,
    'nouvelleValeur' => $values,
    'cavalues' => $cavalues
];

// On transforme le tableau PHP en chaîne JSON
echo json_encode($reponse);
exit;

// En cas d'erreur
echo json_encode(['status' => 'error', 'message' => 'Aucune donnée reçue']);