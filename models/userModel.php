<?php

class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($company_name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO user (company_name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$company_name, $email, $hashedPassword]);
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user["password"])) {
            return $user;
        }
        return null;
    }
}
?>
