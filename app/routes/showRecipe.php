<?php
use Slim\Http\Request;
use Slim\Http\Response;


$app->get("/recipe/{id}", function (Request $request, Response $response, array $args){

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT r.id,
		title,
        description,
        instructions,
        image,
        prep_time,
        cooking_time,
        difficulty_label,
        category_name,
        user_name
	
        FROM recipes as r
        JOIN categories as c
        ON r.category_id = c.id

        JOIN difficulty_levels as d
        ON r.difficulty_id = d.id

        JOIN users as a
        ON r.author_id = a.id
        WHERE r.id = :id";

    $sql2 = "SELECT 
	        ingredient_name,
            i.id 
            FROM recipes_ingredients as ri
            JOIN recipes as r
            ON ri.recipe_id = r.id
            JOIN ingredients as i
            ON ri.ingredient_id = i.id
            WHERE recipe_id = :id;";

    // Récupération des détails de la recette
    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    $recipeDetail = $statement->fetch();


    // Récupératiion des ingrédients
    $statement2 = $this->get("pdo")->prepare($sql2);
    $statement2->execute($args);
    $ingrédients = $statement2->fetchAll();

    return $this->get("view")->render($response, "recipe/details.html.twig", [
        "recipe" => $recipeDetail,
        "ingredientsList" => $ingrédients
    ]);

});

$app->get("/byIngredient/{id}", function (Request $request, Response $response, array $args) {

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT title,
                    image,
                    description,
                    id
                    from recipes
            JOIN recipes_ingredients as ri
            ON ri.recipe_id = :id;";

    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    $recipes = $statement->fetchAll();

   // var_dump($recipes);

    return $this->get("view")->render($response, "recipe/byIngredient.html.twig", [
        "recipe" => $recipes
    ]);

});
