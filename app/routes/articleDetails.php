<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/article/{id}", function ($request, $response, $args){

    //$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    //$id = $args['id'];

    $sql = "SELECT a.id,
		a.title,
	    s.sport_name,
        a.description,
        a.texte,
        a.image,
        u.user_name,
        a.created_at,
        c.comment_text
	
        FROM articles AS a
        JOIN sports AS s
        ON a.sport_id = s.id
        
        LEFT JOIN comments AS c
        ON a.id = c.article_id
        
        JOIN users AS u
        ON a.author_id = u.id
        WHERE a.id = :id";


    // Récupération des détails de l'article'
    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    $articleDetail = $statement->fetch();

    //var_dump($articleDetail);

    return $this->get("view")->render($response, "/articleDetails.html.twig", [
        "article" => $articleDetail
    ]);


});

$app->post("/article/{id}", function (Request $request, Response $response, array $args){
    //on récupère les données
    $commentText = filter_input(INPUT_POST, "commentText", FILTER_SANITIZE_STRING);
    $message = "";
    if(empty($commentText) || strlen($commentText) < 10 ){
        $message = "Veuillez saisir votre commentaire !";
    }
    $id = $request->getParam("id");
    //création de l'objet comment
    $comment = [
        "authorName" => $_SESSION['user'],
        "commentText" => $commentText,
        "idArticle" => $id

    ];

    //requete SQL
    $sql = "INSERT INTO comments(author_name, comment_text, article_id) VALUES(:authorName, :commentText, :idArticle)";
    //Préparation et execution de la requête avec les données à insérer
    $pdo = $this->get("pdo");
    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($comment);

    //redirection
    return $response->withRedirect("/");
});



