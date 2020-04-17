DROP DATABASE IF EXISTS yummy;

CREATE DATABASE yummy DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE yummy;

CREATE TABLE roles
(
    id        TINYINT UNSIGNED AUTO_INCREMENT,
    role_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE difficulty_levels
(
    id               TINYINT UNSIGNED AUTO_INCREMENT,
    difficulty_label VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE categories
(
    id            TINYINT UNSIGNED AUTO_INCREMENT,
    category_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE ingredient_kinds
(
    id        SMALLINT UNSIGNED AUTO_INCREMENT,
    kind_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE tags
(
    id       SMALLINT UNSIGNED AUTO_INCREMENT,
    tag_name VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE users
(
    id            INT UNSIGNED AUTO_INCREMENT,
    user_name     VARCHAR(50)        NOT NULL,
    user_email    VARCHAR(50) UNIQUE NOT NULL,
    user_password VARCHAR(128)       NOT NULL,
    role_id       TINYINT UNSIGNED   NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    CONSTRAINT users_to_roles
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
);

CREATE TABLE recipes
(
    id            MEDIUMINT UNSIGNED AUTO_INCREMENT,
    title         VARCHAR(50)       NOT NULL,
    description   TEXT              NOT NULL,
    instructions  TEXT              NOT NULL,
    image         VARCHAR(50),
    difficulty_id TINYINT UNSIGNED  NOT NULL,
    prep_time     TINYINT UNSIGNED  NOT NULL,
    cooking_time  TINYINT UNSIGNED,
    category_id   TINYINT UNSIGNED  NOT NULL,
    author_id     INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
        CONSTRAINT difficulty_levels_to_recipes
            FOREIGN KEY (difficulty_id)
            REFERENCES difficulty_levels(id),
        CONSTRAINT categories_to_recipes
            FOREIGN KEY (category_id)
            REFERENCES categories(id),
        CONSTRAINT users_to_recipes
            FOREIGN KEY (author_id)
            REFERENCES users(id)
);

CREATE TABLE ingredients
(
    id              SMALLINT UNSIGNED AUTO_INCREMENT,
    ingredient_name VARCHAR(30)      NOT NULL,
    kind_id         SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
        CONSTRAINT ingredient_kinds_to_ingredient
        FOREIGN KEY (kind_id)
        REFERENCES ingredient_kinds(id)
);

CREATE TABLE recipes_ingredients
(
    ingredient_id SMALLINT UNSIGNED,
    recipe_id     MEDIUMINT UNSIGNED,
    PRIMARY KEY (ingredient_id, recipe_id),
    CONSTRAINT ingredient_id_to_recipe_id
    FOREIGN KEY (ingredient_id)
    REFERENCES ingredients (id),
    CONSTRAINT recipe_id_to_ingredient_id
    FOREIGN KEY (recipe_id)
    REFERENCES recipes(id)
);

CREATE TABLE recipe_books
(
    user_id    INT UNSIGNED,
    recipe_id  MEDIUMINT UNSIGNED,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, recipe_id)
);

CREATE TABLE recipe_comments
(
    id            INT UNSIGNED AUTO_INCREMENT,
    author_name   VARCHAR(50) NOT NULL,
    author_email  VARCHAR(50) NOT NULL,
    comment_text  TEXT        NOT NULL,
    created_at    TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    recipe_rating TINYINT UNSIGNED,
    recipe_id     MEDIUMINT UNSIGNED,
    PRIMARY KEY (id),
     CONSTRAINT recipe_comments_to_recipes
        FOREIGN KEY (recipe_id)
        REFERENCES recipes(id)
);

CREATE TABLE recipes_tags
(
    tag_id    SMALLINT UNSIGNED,
    recipe_id MEDIUMINT UNSIGNED,
    PRIMARY KEY (tag_id, recipe_id),
        CONSTRAINT recipes_tags_to_tags
            FOREIGN KEY (tag_id)
            REFERENCES  tags(id),
        CONSTRAINT recipes_tags_to_recipes
            FOREIGN KEY (recipe_id)
            REFERENCES recipes(id)
);

-- Insertion des catégories
INSERT INTO categories (category_name)
    VALUES ('Apéritf et buffet'), ('Entrée'), ('Plat principal'), ('Dessert');

