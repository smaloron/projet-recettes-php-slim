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


    $sql3 = "SELECT tag_name, t.id
            FROM recipes_tags as rt

            JOIN recipes as r
            ON rt.recipe_id = r.id

            JOIN tags as t 
            ON rt.tag_id = t.id

            where r.id = :id;";



    // Récupération des détails de la recette
    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    $recipeDetail = $statement->fetch();


    // Récupératiion des ingrédients liés à cette recette
    $statement2 = $this->get("pdo")->prepare($sql2);
    $statement2->execute($args);
    $ingrédients = $statement2->fetchAll();


    // Récupération des tags de la recette
    $statement3 = $this->get("pdo")->prepare($sql3);
    $statement3->execute($args);
    $tags = $statement3->fetchAll();

    return $this->get("view")->render($response, "recipe/details.html.twig", [
        "recipe" => $recipeDetail,
        "ingredientsList" => $ingrédients,
        'tags' => $tags
    ]);

});

$app->get("/byIngredient/{id}", function (Request $request, Response $response, array $args) {

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);


    $sql = "SELECT * from recipes_ingredients

            JOIN recipes as r
            ON r.id = recipe_id

            JOIN ingredients as i
            ON i.id = recipe_id

        WHERE ingredient_id = :id;";

    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    $recipes = $statement->fetchAll();

   // var_dump($recipes);

    return $this->get("view")->render($response, "recipe/byIngredient.html.twig", [
        "recipe" => $recipes
    ]);

});


$app->get("/byTag/{id}", function (Request $request, Response $response, array $args) {

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

    $sql = "SELECT * from recipes_tags
            JOIN recipes as r
            ON r.id = recipe_id
            JOIN tags as t
            ON t.id = recipe_id
            WHERE tag_id = 4;";

    $statement = $this->get("pdo")->prepare($sql);
    $statement->execute($args);
    $recipes = $statement->fetchAll();


    //var_dump($recipes);



    return $this->get("view")->render($response, "recipe/byTag.html.twig", [
        "recipe" => $recipes
    ]);
});
