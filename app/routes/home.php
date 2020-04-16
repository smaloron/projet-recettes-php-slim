<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/", function (Request $request, Response $response){
   $this->view->render($response, "home.html.twig", []);
});