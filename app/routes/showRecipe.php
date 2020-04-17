<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/recipe", function (Request $request, Response $response){
    $this->view->render($response, "recipe/details.html.twig", []);
});
