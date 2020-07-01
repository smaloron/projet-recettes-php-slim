<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/sports-list", function (Request $request, Response $response){

    $recordSet = $this->get("pdo")->query("SELECT * FROM sports");

    return $this->get("view")->render($response, "sportsList.html.twig", [
        "sportsList" => $recordSet->fetchAll()
    ]);

});

$app->get("/sports-list/{id}", function (Request $request, Response $response, array $args){
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    $sql = "DELETE FROM sports WHERE id = :id";

    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    return $response->withRedirect("/sports-list");
});


$app->post("/sports-list", function (Request $request, Response $response, array $args){
    //On récupère les données
    $sportName = filter_input(INPUT_POST, "sportName", FILTER_SANITIZE_STRING);

    //création de l'objet Sport
    if(!empty($sportName)){
        $sport = [
            "sportName" => $sportName
        ];
    }else{
        $error = "Saisie incorrecte";
    }

    //Requete SQL
    $sql = "INSERT INTO sports(sport_name) VALUES(:sportName)";
    //Préparation et execution de la requete d'insertion
    $pdo = $this->get("pdo");
    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($sport);
    return $response->withRedirect("/sports-list");
});