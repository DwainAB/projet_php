<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Authorization");
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Content-Type: application/json');

require_once "config/database.php";
require_once 'controllers/jobsController.php';
require_once 'models/jobModel.php';

// Créez une instance du modèle JobModel
$model = new JobModel($pdo);

// Créez une instance du contrôleur JobController avec le modèle
$controller = new JobController($model);

// Incluez le fichier router.php
require_once 'routes/router.php';

// Analyse de l'URL actuelle et gestion de la demande
$requestUri = strtok($_SERVER["REQUEST_URI"], '?');
handleRequest($requestUri, $controller);

?>
