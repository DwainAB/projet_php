<?php

// Permet d'éviter les problèmes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, Access-Control-Allow-Headers, X-Requested-With");

// Permet de gérer les requettes OPTION
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

header('Content-Type: application/json');

require_once "config/database.php";
require_once 'controllers/jobsController.php';
require_once 'models/jobModel.php';
require_once 'controllers/userController.php';
require_once 'models/userModel.php';  

// on creer une instance du modèle JobModel et du contrôleur JobController avec le modèle
$model = new JobModel($pdo);
$controller = new JobController($model);

$userModel = new UserModel($pdo);
$userController = new UserController($userModel);

require_once 'routes/router.php';

$requestUri = strtok($_SERVER["REQUEST_URI"], '?');
$endpointParts = explode('/', $requestUri);
if (in_array('register', $endpointParts) || in_array('login', $endpointParts)) {
    handleRequest($requestUri, $userController);
} else {
    handleRequest($requestUri, $controller);
}

?>
