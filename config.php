<?php
// config.php

// 1. Chemin absolu système (pour include/require)
define('ROOT_PATH', __DIR__ . '/');

// 2. Détection dynamique du protocole (http ou https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

// 3. Détection de l'hôte (ex: localhost ou mon-site.com)
$host = $_SERVER['HTTP_HOST'];

// 4. Détection du dossier racine du projet par rapport au DocumentRoot
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); // Normalise les antislashes Windows
$doc_root   = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// On calcule le chemin relatif du projet par rapport à la racine Web
$project_dir = str_replace($doc_root, '', str_replace('\\', '/', __DIR__));

// On construit l'URL de base finale
define('BASE_URL', $protocol . $host . rtrim($project_dir, '/') . '/');


define('BASE_URL_VUE', $protocol . $host . rtrim($project_dir, '/') . '/vue/');
define('BASE_URL_CONTROLER', $protocol . $host . rtrim($project_dir, '/') . '/controler/');
define('BASE_URL_MODELE', $protocol . $host . rtrim($project_dir, '/') . '/modele/');
define('BASE_URL_STYLE', $protocol . $host . rtrim($project_dir, '/') . '/styles/');
define('BASE_URL_IMAGE', $protocol . $host . rtrim($project_dir, '/') . '/images/');
define('BASE_URL_SCRIPT', $protocol . $host . rtrim($project_dir, '/') . '/scripts/');
?>