<?php
class JobController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }



///////////////////////////////////////////////////////////////////////////////////////////

    public function test(){
        header('Content-Type: application/json');
        echo json_encode(["message" => "subskill API - Test"]); // Modifiez le message au besoin
    } 

///////////////////////////////////////////////////////////////////////////////////////////

    //Fonction qui permet de récupérer tous les jobs
    public function list() {

        header('Content-Type: application/json'); //on précise que l'on va envoyer du JSON

        $offres = $this->model->getAllJobs();

        if (empty($offres)) { //si aucun job n'est trouvé
            echo "Aucune offre d'emploi trouvée.";
            return; 
        }

        echo json_encode($offres, JSON_UNESCAPED_UNICODE);
    }

///////////////////////////////////////////////////////////////////////////////////////////

    public function add() {
        // Vérifiez si les données ont été envoyées via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collectez les données soumises
            $data = [
                'title_job' => $_POST['title_job'],
                'company_name' => $_POST['company_name'],
                'description_job' => $_POST['description_job'],
                'location' => $_POST['location'],
                'type_contract' => $_POST['type_contract'],
                'type_job' => $_POST['type_job'],
            ];

            // Appelez la fonction addJob de votre modèle
            $success = $this->model->addJob($data);

            if ($success) {
                http_response_code(201); // 201 Created
                echo json_encode(["message" => "Job ajouté avec succès."]);
            } else {
                http_response_code(500); // 500 Internal Server Error
                echo json_encode(["error" => "Une erreur est survenue lors de l'ajout du job."]);
            }
        } else {
            http_response_code(400); // 400 Bad Request
            echo json_encode(["error" => "Mauvaise méthode de requête. Veuillez utiliser POST."]);
        }
    }
    
///////////////////////////////////////////////////////////////////////////////////////////

public function edit($id) {
        // Logique pour modifier une annonce spécifique (identifiée par $id)
        // Utilisez $this->pdo pour accéder à la base de données
    }

///////////////////////////////////////////////////////////////////////////////////////////

    public function delete($id) {
        if ($this->model->deleteJob($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => 'Offre d\'emploi supprimée avec succès']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
    }
    
///////////////////////////////////////////////////////////////////////////////////////////
    public function filter() {
        header('Content-Type: application/json'); // On précise que l'on va envoyer du JSON

        $type_contract = isset($_GET['type_contract']) ? $_GET['type_contract'] : null;
        $location = isset($_GET['location']) ? $_GET['location'] : null;
        $type_job = isset($_GET['type_job']) ? $_GET['type_job'] : null;

        $offres = $this->model->getFilteredJobs($type_contract, $location, $type_job);

        if (empty($offres)) { // Si aucun job n'est trouvé avec les critères fournis
            echo json_encode(["message" => "Aucune offre d'emploi trouvée avec ces critères."]);
            return; 
        }

        echo json_encode($offres, JSON_UNESCAPED_UNICODE);
    }
}