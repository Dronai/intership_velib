<?php

define("USER", "root");//je défini le nom d'utilisateur pour se connecter à la base de donné

define("PASSWORD", "");//je défini le mot de passe

define("DNS", 'mysql:host=localhost;dbname=velib');

try {
    $pdo = new PDO(DNS, USER, PASSWORD);
}//je crée mon objet PDO qui va me servir plus tard pour les requêtes

catch (PDOException $e) {

    die($e->getMessage());

}

