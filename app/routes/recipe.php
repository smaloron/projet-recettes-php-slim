<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/recipeForm", function (Request $request, Response $response){
    $pdo = $this->get("pdoRecipe");
    $categoryList = $pdo->query("SELECT * FROM categories");
    $difficultyList = $pdo->query("SELECT * FROM difficulty_levels");

    $this->view->render($response, "recipeForm.html.twig", [
        "categoryList" => $categoryList->fetchAll(),
        "difficultyList" => $difficultyList->fetchAll(),
    ]);
});

$app->post("/recipeForm", function(Request $request, Response $response){
    //On récupère les données
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, "category", FILTER_SANITIZE_NUMBER_INT);
    $difficulty = filter_input(INPUT_POST, "difficulty", FILTER_SANITIZE_NUMBER_INT);
    $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
    $instructions = filter_input(INPUT_POST, "instructions", FILTER_SANITIZE_STRING);
    $preparation = filter_input(INPUT_POST, "preparation", FILTER_SANITIZE_NUMBER_INT);
    $cooking = filter_input(INPUT_POST, "cooking", FILTER_SANITIZE_NUMBER_INT);


    //Récupération de la saisie des ingrédients
    $ingredientString = filter_input(INPUT_POST, "ingredient", FILTER_SANITIZE_STRING);

    //Transformation de la sasie des ingredients en tableau avec la fonction explode
    $ingredientList = explode(",", $ingredientString);

    //Suppression des espaces potentiels avant/après les tags
    $ingredientList = array_map(function($item){
        return trim($item);
    }, $ingredientList);

    //Unicité de l'ingrédient
    $ingredientsList = array_unique($ingredientList);

    //Récupération de la saisie des tags
    $tagString = filter_input(INPUT_POST, "tags", FILTER_SANITIZE_STRING);

    //Transformation de la saisie des tags en tableau avec la fonction explode
    $tagList = explode(",", $tagString);

    //Suppression des espaces potentiels avant/après les tags
    $tagList = array_map(function($item){
        return trim($item);
    }, $tagList);

    //Unicité du tag
    $tagsList = array_unique($tagList);

    //Création de la structure de données persistante
    $recipe = [
        "title" => $title,
        "category_id" => $category,
        "difficulty_id" => $difficulty,
        "description" => $description,
        "instructions" => $instructions,
        "prep_time" => $preparation,
        "cooking_time" => $cooking,
        "author_id" => $_SESSION["user"]["id"]
    ];


    // Récupération de l'identifiant
    $id =$request->getParam("id");

    //Requete SQL -  préparation - execution
    $sql = "INSERT INTO recipes(title, category_id, difficulty_id, description, instructions, prep_time, cooking_time, author_id) 
            VALUES (:title, :category_id, :difficulty_id, :description, :instructions, :prep_time, :cooking_time, :author_id) ";

    //Préparation et execution de la requête avec les données à insérer
    $pdo = $this->get("pdoRecipe");
    $statement = $this->get("pdoRecipe")->prepare($sql);
    $statement->execute($recipe);

    //Gestion des ingredients
    if(empty($id)) {
        $id = $pdo->lastInsertId();
        $ingredientsId = [];
        $sql = "SELECT id FROM ingredients WHERE ingredient_name=?";
        $statement = $pdo->prepare($sql);

        $sql = "INSERT INTO ingredients (ingredient_name) VALUES (?)";
        $ingredientInsertStatement = $pdo->prepare($sql);

        //Recherche/création des ingredients de la saisie
        foreach ($ingredientList as $ingredientName) {
            $statement->execute([$ingredientName]);
            $recordSet = $statement->fetch();
            if ($recordSet) {
                array_push($ingredientsId, $recordSet["id"]);
            } else {
                $ingredientInsertStatement->execute([$ingredientName]);
                array_push($ingredientsId, $pdo->LastInsertId());
            }
        }
        //Assignation des ingredients à la recette
        $sql = "INSERT INTO recipes_ingredients (ingredient_id, recipe_id) VALUES (?,?)";
        $ingredientAssignationStatement = $pdo->prepare($sql);

        foreach ($ingredientsId as $ingredientId) {
            $ingredientAssignationStatement->execute([$id, $ingredientId]);
        }
    }

    //Gestion des tags
    if(empty($id)){
        $id = $pdo->lastInsertId();
        $tagsId = [];
        $sql = "SELECT id FROM tags WHERE tag_name=?";
        $statement = $pdo->prepare($sql);

        $sql = "INSERT INTO tags (tag_name) VALUES (?)";
        $tagInsertStatement = $pdo->prepare($sql);

        //Recherche/création des tags de la saisie
        foreach ($tagList as $tagName){
            $statement->execute([$tagName]);
            $recordSet = $statement->fetch();
            if($recordSet){
                array_push($tagsId, $recordSet["id"]);
            } else {
                $tagInsertStatement->execute([$tagName]);
                array_push($tagsId, $pdo->LastInsertId());
            }
        }
        //Assignation des tags à la tâche
        $sql = "INSERT INTO recipes_tags (tag_id, recipe_id) VALUES (?,?)";
        $tagAssignationStatement = $pdo->prepare($sql);

        foreach ($tagsId as $tagId){
            $tagAssignationStatement->execute([$id, $tagId]);
        }
    }

    //Redirection vers le formulaire
    return $response->withRedirect("/recipeForm");
});