<?php

$db_server = "localhost";
// $db_server = "mysql-viteetgourmand23.alwaysdata.net";
// $db_user = "root";
// $db_pass = "";
// $db_name = "viteetgourmand";
$pdo = "";

$db_user = "viteetgourmand23";
$db_pass = "bddviteetgourmand23*";
$db_name = "viteetgourmand23_bdd";

define("MIN_NOTE_AVIS",3);

try {
    $pdo = new PDO('mysql:host=' . $db_server . ';dbname=' . $db_name, $db_user, $db_pass);
    // echo 'Connecté à la base de données !!!';

} catch (PDOexception $e) {
    // echo 'Erreur de connexion à la base de données';
}
?>
