<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/recipeForm", function (Request $request, Response $response){
    $this->view->render($response, "recipeForm.html.twig", []);
});