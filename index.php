<?php

// Ajoutez les headers CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Access-Control-Allow-Headers, X-Requested-With");

// Gérez les requêtes OPTIONS (requêtes "preflight")
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

header('Content-Type: application/json');

require_once "config/database.php";
require_once 'controllers/jobsController.php';
require_once 'models/jobModel.php';

require_once 'controllers/userController.php';
require_once 'models/userModel.php';  // attention à la faute de syntaxe manquante ; ici

// Créez une instance du modèle JobModel
$model = new JobModel($pdo);

// Créez une instance du contrôleur JobController avec le modèle
$controller = new JobController($model);

// Créez une instance du modèle UserModel et du contrôleur UserController
$userModel = new UserModel($pdo);
$userController = new UserController($userModel);

// Incluez le fichier router.php
require_once 'routes/router.php';

// Analyse de l'URL actuelle et gestion de la demande
$requestUri = strtok($_SERVER["REQUEST_URI"], '?');
$endpointParts = explode('/', $requestUri);
if (in_array('register', $endpointParts) || in_array('login', $endpointParts)) {
    handleRequest($requestUri, $userController);
} else {
    handleRequest($requestUri, $controller);
}

?>
