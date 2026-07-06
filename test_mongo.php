<?php
if (extension_loaded('mongodb')) {
    echo "<h1>✅ Succès !</h1>";
    echo "L'extension MongoDB est maintenant active sur votre compte.";
} else {
    echo "<h1>❌ Échec</h1>";
    echo "L'extension n'est toujours pas chargée.<br>";
    echo "Vérifiez le chemin dans le champ php.ini personnalisé : " . php_ini_loaded_file();
}


use MongoDB\Driver\ServerApi;

$uri = 'mongodb+srv://viteetgourmand23:bddviteetgourmand23*@viteetgourmand23.ogwj3z9.mongodb.net/?appName=ViteetGourmand23';

// Set the version of the Stable API on the client
$apiVersion = new ServerApi(ServerApi::V1);

// Create a new client and connect to the server
$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);

try {
    // Send a ping to confirm a successful connection
    $client->selectDatabase('admin')->command(['ping' => 1]);
    echo "Pinged your deployment. You successfully connected to MongoDB!\n";
} catch (Exception $e) {
    printf($e->getMessage());
}