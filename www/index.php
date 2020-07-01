<?php
session_start();
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
require "../app/routes/sportsList.php";
require "../app/routes/article.php";
require "../app/routes/register.php";
require "../app/routes/login.php";
require "../app/routes/videos.php";
require "../app/routes/articleDetails.php";


//lancement de l'application
$app->run();