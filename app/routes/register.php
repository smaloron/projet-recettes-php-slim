<?php

use Slim\Http\Response;
use Slim\Http\Request;

    //affichage du formulaire
$app->get("/register", function (Request $request, Response $response){
    $this->view->render($response, "register.html.twig", []);
});

    //traitement des données postées
    $app->post("/register", function (Request $request, Response $response) {
        //récupération des données
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $plainPassword = filter_input(INPUT_POST, "password", FILTER_DEFAULT);
        $role_id = filter_input(INPUT_POST, "role_id", FILTER_SANITIZE_NUMBER_INT);

        //tableau des erreurs
        $errors = [];
        //traitement
        if (empty($name)) {
            array_push($errors, "Le nom doit être renseigné");
        } else if (mb_strlen($name) < 4) {
            array_push($errors, "Le nom doit comporter plus de 3 caractères");
        }
        if (empty($email)) {
            array_push($errors, "l'adresse email doit être renseingée");
        }
        if (empty($plainPassword)) {
            array_push($errors, "Le mot de passe ne peut être vide");
        } else if (mb_strlen($plainPassword) < 6) {
            array_push($errors, "Le mot de passe doit comporter plus de 5 caractères");
        }

        if (empty($errors)) {
            //Requête SQL
            $sql = "INSERT INTO users(user_name, user_email, user_password, role_id) 
                VALUES(?, ?, ?, ?)";
            //tableau des params
            $params = [
                $name,
                $email,
                password_hash($plainPassword, PASSWORD_DEFAULT),
                $role_id
            ];
            //préparation de la requête + exécution
            $statement = $this->get("pdo")->prepare($sql);
            $statement->execute($params);
            //redirection
            return $response->withRedirect("/");
        }else{
            $this->view->render($response, "register.html.twig", ["errors"=>$errors]);
        }

    });