<?php
class JobController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }


    /////////// Fonction de test

    public function test() {
        header('Content-Type: application/json');
        echo json_encode(["message" => "subskill API - Test"]);
    }


    /////////// Fonction qui permet de récupérer toutes les offres
    public function list() {
        header('Content-Type: application/json');
    
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    
        $offres = $this->model->getAllJobs($page);
    
        if (empty($offres)) {
            http_response_code(404);
            echo json_encode(["message" => "Aucune offre d'emploi trouvée."]);
            return;
        }
    
        echo json_encode($offres, JSON_UNESCAPED_UNICODE);
    }
    

    ///////////// Fonction qui permet d'ajouter une offre
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $keys = ['title_job', 'company_name', 'description_job', 'location', 'type_contract', 'type_job'];
            $data = [];

            foreach ($keys as $key) {
                $data[$key] = isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : null;
            }

            if (in_array(null, $data, true)) {
                http_response_code(400);
                echo json_encode(["error" => "Données incomplètes ou incorrectes."]);
                return;
            }

            $success = $this->model->addJob($data);

            if ($success) {
                http_response_code(201);
                echo json_encode(["message" => "Job ajouté avec succès."]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Une erreur est survenue lors de l'ajout du job."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Mauvaise méthode de requête. Veuillez utiliser POST."]);
        }
    }
    
    ////////////// Fonction qui permet de supprimer une offre
    public function delete($id) {
        if ($this->model->deleteJob($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => 'Offre d\'emploi supprimée avec succès']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
    }

    ////////////// Fonction qui permet de filtrer les offres
    public function filter() {
        header('Content-Type: application/json');

        $keys = ['type_contract', 'location', 'type_job'];
        $filters = [];

        foreach ($keys as $key) {
            $filters[$key] = isset($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8') : null;
        }

        $offres = $this->model->getFilteredJobs($filters['type_contract'], $filters['location'], $filters['type_job']);

        if (empty($offres)) {
            http_response_code(404);
            echo json_encode(["message" => "Aucune offre d'emploi trouvée avec ces critères."]);
            return; 
        }

        echo json_encode($offres, JSON_UNESCAPED_UNICODE);
    }

    
    ////////////// Fonction qui permet de modifier une offre
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $keys = ['title_job', 'company_name', 'description_job', 'location', 'type_contract', 'type_job'];
            $data = [];
    
            foreach ($keys as $key) {
                $data[$key] = isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : null;
            }
    
            if (in_array(null, $data, true)) {
                http_response_code(400);
                echo json_encode(["error" => "Données incomplètes ou incorrectes."]);
                return;
            }
    
            $success = $this->model->updateJob($id, $data);
    
            if ($success) {
                http_response_code(200);
                echo json_encode(["message" => "Job mis à jour avec succès."]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "Une erreur est survenue lors de la mise à jour du job."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Mauvaise méthode de requête. Veuillez utiliser POST."]);
        }
    }
    
}
