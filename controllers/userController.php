<?php
require_once "./vendor/autoload.php";
require_once "./middleware/JWTMiddleware.php";

use \Firebase\JWT\JWT;  

class UserController {
    private $model;
    private $key = "clé_secrète";  

    public function __construct($model) {
        $this->model = $model;
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->company_name) && isset($data->email) && isset($data->password)) {
            $success = $this->model->register($data->company_name, $data->email, $data->password);
            if ($success) {
                $payload = array(
                    "email" => $data->email,
                    "exp" => time() + (60*60)  
                );
                $jwt = JWT::encode($payload, $this->key, 'HS256');
                
                
                echo json_encode(["message" => "Inscription réussie", "token" => $jwt,  "user" => $user]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'inscription"]);
            }
        } else {
            echo json_encode(["error" => "Données manquantes"]);
        }
    }
    

    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->email) && isset($data->password)) {
            $user = $this->model->login($data->email, $data->password);
            if ($user) {
                
                $payload = array(
                    "email" => $data->email,
                    "exp" => time() + (60*60)  
                );
    
                $jwt = JWT::encode($payload, $this->key, 'HS256');
    
                echo json_encode(["message" => "Connexion réussie", "token" => $jwt, "user" => $user]);
            } else {
                echo json_encode(["error" => "Identifiants incorrects"]);
            }
        } else {
            echo json_encode(["error" => "Données manquantes"]);
        }
    }
}
