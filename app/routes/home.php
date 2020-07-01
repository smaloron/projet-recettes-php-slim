<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/", function (Request $request, Response $response){
    $pdo = $this->get("pdoArticle");
    $articlesList = $pdo->query("SELECT * FROM articles WHERE published_at < NOW()");
   $this->view->render($response, "home.html.twig", [
       "articlesList" => $articlesList->fetchAll(),
   ]);
});