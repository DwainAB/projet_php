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

    public function deleteJob($id) {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}
