<?php

define("USER", "boutinpfbedronai");//je défini le nom d'utilisateur pour se connecter à la base de donné

define("PASSWORD", "Dr0naiMan9ePomme5");//je défini le mot de passe

define("DNS", 'mysql:host=boutinpfbedronai.mysql.db;dbname=boutinpfbedronai');

try {
    $pdo = new PDO(DNS, USER, PASSWORD);
}//je crée mon objet PDO qui va me servir plus tard pour les requêtes

catch (PDOException $e) {
    die($e->getMessage());
}