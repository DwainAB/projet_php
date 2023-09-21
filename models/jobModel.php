<?php

class JobModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    ////////////// Fonction qui permet de récupérer les offres et pagination
    public function getAllJobs($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM jobs LIMIT :offset, :perPage";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    ////////////// Fonction qui permet de filtrer 
    public function getFilteredJobs($type_contract = null, $location = null, $type_job = null) {
        $query = "SELECT * FROM jobs WHERE 1=1";
        if ($type_contract) {
            $query .= " AND type_contract = :type_contract";
        }
        if ($location) {
            $query .= " AND location = :location";
        }
        if ($type_job) {
            $query .= " AND type_job = :type_job";
        }

        $stmt = $this->pdo->prepare($query);
        if ($type_contract) {
            $stmt->bindParam(':type_contract', $type_contract);
        }
        if ($location) {
            $stmt->bindParam(':location', $location);
        }
        if ($type_job) {
            $stmt->bindParam(':type_job', $type_job);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    ////////////// Fonction qui permet d'ajouter une offre
    public function addJob($data) {
        $stmt = $this->pdo->prepare("INSERT INTO jobs (publication_date, update_job, reference, title_job, company_name, description_job, location, type_contract, type_job, image_url) VALUES (CURDATE(), CURDATE(), :reference, :title, :company_name, :description, :location, :type_contract, :type_job, :image_url)");

        $reference = $this->generateUniqueReference();
    
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':title', $data['title_job']);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->bindParam(':description', $data['description_job']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':type_contract', $data['type_contract']);
        $stmt->bindParam(':type_job', $data['type_job']);

        $image_url = $this->getRandomImageUrl();
        $stmt->bindParam(':image_url', $image_url);

        return $stmt->execute();
    }

    // Fonction pour obtenir une URL d'image aléatoire de Lorem Picsum
    private function getRandomImageUrl($width = 300, $height = 200) {
        $baseUrl = "https://picsum.photos";
        $url = "{$baseUrl}/{$width}/{$height}";
        return $url;
    }

    //  Fonction qui permet d'avoir une référence pour chaques offres, chaques offres a une référence unique'
    private function generateUniqueReference() {
        do {
            $reference = mt_rand(10000, 99999);
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM jobs WHERE reference = :reference");
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } while ($result['count'] > 0);

        return $reference;
    }

    ////////////// Fonction qui permet de supprimer une offre
    public function deleteJob($id) {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    ////////////// Fonction qui permet de modifier une offre
    public function updateJob($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE jobs SET update_job = CURDATE(), title_job = :title, company_name = :company_name, description_job = :description, location = :location, type_contract = :type_contract, type_job = :type_job WHERE id = :id");
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title_job']);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->bindParam(':description', $data['description_job']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':type_contract', $data['type_contract']);
        $stmt->bindParam(':type_job', $data['type_job']);
    
        return $stmt->execute();
    }
}

?>
