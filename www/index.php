<?php

error_reporting(E_ALL);

// Déclaration des classes dont on aura besoin
use Slim\App;

//Inclusion de fichier d'autochargement des classes de vendor
require "../vendor/autoload.php";

require "../app/config.php";
$container = require "../app/container.php";

//Création de l'application
//En passant le container en argument
$app = new App($container);

require "../app/routes/home.php";


require "../app/routes/register.php";

//lancement de l'application
$app->run();