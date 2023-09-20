<?php

class JobModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllJobs() {
        try {
            // Requête SQL pour récupérer toutes les offres d'emploi
            $query = "SELECT * FROM jobs";
            $stmt = $this->pdo->query($query);

            // Récupérer les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function getFilteredJobs($type_contract = null, $location = null, $type_job = null) {
        try {
            // Début de la requête SQL
            $query = "SELECT * FROM jobs WHERE 1=1";
            
            // Si les filtres sont fournis, les ajouter à la requête
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

            // Associer les paramètres si ils sont fournis
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

            // Récupérer les résultats sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }
    }

    public function addJob($data) {
        $stmt = $this->pdo->prepare("INSERT INTO jobs (publication_date, update_job, reference, title_job, company_name, description_job, location, type_contract, type_job) VALUES (CURDATE(), CURDATE(), :reference, :title, :company_name, :description, :location, :type_contract, :type_job)");

        // Générer une référence unique
        $reference = $this->generateUniqueReference();

        // Associer les paramètres et leurs valeurs
        $stmt->bindParam(':reference', $reference);
        $stmt->bindParam(':title', $data['title_job']);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->bindParam(':description', $data['description_job']);
        $stmt->bindParam(':location', $data['location']);
        $stmt->bindParam(':type_contract', $data['type_contract']);
        $stmt->bindParam(':type_job', $data['type_job']);

        return $stmt->execute();
    }

    private function generateUniqueReference() {
        do {
            $reference = mt_rand(10000, 99999);  // Générer un nombre aléatoire entre 10000 et 99999
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM jobs WHERE reference = :reference");
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } while ($result['count'] > 0);

        return $reference;
    }

    public function deleteJob($id) {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
