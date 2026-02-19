<?php

$db_server = "localhost";
// $db_user = "root";
// $db_pass = "";
// $db_name = "viteetgourmand";
$pdo = "";

$db_user = "431868_db";
$db_pass = "Rfernand23*";
$db_name = "viteetgourmand";

define("MIN_NOTE_AVIS",3);

try {
    $pdo = new PDO('mysql:host=' . $db_server . ';dbname=' . $db_name, $db_user, $db_pass);
    // echo 'Connecté à la base de données !!!';

} catch (PDOexception $e) {
    // echo 'Erreur de connexion à la base de données';
}
?>