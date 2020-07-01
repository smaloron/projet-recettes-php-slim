<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/login', function (Request $request, Response $response){
    return $this->view->render($response, 'login.html.twig', []);
});

$app->post('/login', function (Request $request, Response $response){
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_DEFAULT);

    $sql = "SELECT * FROM users WHERE user_email=?";
    $statement = $this->pdo->prepare($sql);
    $statement->execute([$email]);
    $user = $statement->fetch();

    if($user && password_verify($password, $user["user_password"])){
        unset($user["user_password"]);
        $_SESSION["user"] = $user;
        //redirection
        return $response->withRedirect("/");
    }else{
        $error = "erreur d'authentification ";
        $this->view->render($response, "login.html.twig", ["error" => $error]);
    }

});
