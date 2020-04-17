<?php

use Slim\Container;
use Slim\Views\Twig;

// Configuration de l'application
$conf = [
    "settings" => [
        "displayErrorDetails" => true
    ]
];

//Création d'une instance du container
$container = new Container($conf);

/************************************
 *  Définition des entrées du container
 **************************************/

//Entrée du container pour stocker une instance de PDO
$container["pdo"] = function(){
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8;port=8889";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    return new PDO($dsn, DB_USER, DB_PASS, $options);
};

$container["pdoRecipe"] = function(){
    $dsn = "mysql:host=127.0.0.1;dbname=yummy;charset=utf8";
    $user = "root";
    $pass = "";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    return new PDO($dsn, $user, $pass, $options);
};

//Configuration du moteur de template Twig
$container["view"] = function(){
    return new Twig("../templates"
    //,["cache" => "../cache"]
    );
};


// Retourne le container pour la création de l'application dans index.php
return $container;