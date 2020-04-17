<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/login', function (Request $request, Response $response){
    return $this->view->render($response, 'login.html.twig', []);
});
