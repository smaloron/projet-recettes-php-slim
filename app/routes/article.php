<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/articleForm", function (Request $request, Response $response){
    $pdo = $this->get("pdoArticle");
    $sportsList = $pdo->query("SELECT * FROM sports");

    $this->view->render($response, "articleForm.html.twig", [
        "sportsList" => $sportsList->fetchAll()
    ]);
});

$app->post("/articleForm", function(Request $request, Response $response){
    //On récupère les données
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
    $sport = filter_input(INPUT_POST, "sport", FILTER_SANITIZE_NUMBER_INT);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
    $texte= filter_input(INPUT_POST, "texte", FILTER_SANITIZE_STRING);
    $image = filter_input(INPUT_POST, "image", FILTER_DEFAULT);
    $publishedAt = filter_input(INPUT_POST, "publishedAt", FILTER_DEFAULT);

    $error			= [];
    if (empty($title) ||  strlen($title) < 3) {
        $error[] = "title";
    }
    if (empty($sport) ||  ! is_numeric($sport)) {
        $error[] = "sport";
    }
    if (empty($description) ||  strlen($description) < 3) {
        $error[] = "description";
    }
    if (empty($texte) ||  strlen($texte) < 10) {
        $error[] = "texte";
    }

    if (count($error) > 0) {
        //Redirection vers le formulaire
        $pdo = $this->get("pdoArticle");
        $sportsList = $pdo->query("SELECT * FROM sports");

        return $this->view->render($response, "articleForm.html.twig", [
            "sportsList" => $sportsList->fetchAll(),
            "errors" => $error

        ]);
    }
    //Création de la structure de données persistante
    $article = [
        "title" => $title,
        "sport" => $sport,
        "description" => $description,
        "texte" => $texte,
        "image" => $image,
        "publishedAt" => $publishedAt,
        "author_id" => $_SESSION["user"]["id"]
    ];

    // Récupération de l'identifiant
   // $id =$request->getParam("id");


    //Requete SQL -  préparation - execution
    $sql = "INSERT INTO articles(title, description, texte, image, sport_id, author_id, published_at) 
            VALUES (:title, :description, :texte, :image, :sport, :author_id, :publishedAt) ";
    var_dump($article);
    //Préparation et execution de la requête avec les données à insérer
    $pdo = $this->get("pdoArticle");
    $statement = $this->get("pdoArticle")->prepare($sql);
    $statement->execute($article);



    //Redirection vers le formulaire
    return $response->withRedirect("/");
});