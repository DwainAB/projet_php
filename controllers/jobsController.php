<?php
class JobController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function test(){
        header('Content-Type: application/json');
        echo json_encode(["message" => "subskill API - Test"]); // Modifiez le message au besoin
    } 


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

    public function edit($id) {
        // Logique pour modifier une annonce spécifique (identifiée par $id)
        // Utilisez $this->pdo pour accéder à la base de données
    }

    public function delete($id) {
        if ($this->model->deleteJob($id)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => 'Offre d\'emploi supprimée avec succès']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
    }
    

}
