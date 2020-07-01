<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get("/videos", function (Request $request, Response $response){

    $recordSet = $this->get("pdo")->query("SELECT * FROM medias");

    return $this->get("view")->render($response, "videos.html.twig", [
        "videos" => $recordSet->fetchAll()
    ]);
    var_dump($recordSet->fetchAll());

});