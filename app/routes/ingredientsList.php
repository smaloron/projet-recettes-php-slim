<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/ingredients-list", function (Request $request, Response $response){

    $recordSet = $this->get("pdo")->query("SELECT * FROM view_ingredientsList");

    return $this->get("view")->render($response, "ingredientsList.html.twig", [
        "ingredientsList" => $recordSet->fetchAll()
    ]);

});