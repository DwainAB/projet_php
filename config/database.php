<?php

$host = "localhost";
$dbname = "test_subskill";
$username = "root";
$password = "root";

try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;", $username, $password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
    die("Erreur de connexion à la base de donnée : " . $e->getMessage());
}
