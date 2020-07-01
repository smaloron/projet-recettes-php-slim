DROP DATABASE IF EXISTS sportsDB;

CREATE DATABASE sportsDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


USE sportsDB;

CREATE TABLE roles
(
    id        TINYINT UNSIGNED AUTO_INCREMENT,
    role_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sports
(
    id            TINYINT UNSIGNED AUTO_INCREMENT,
    category_name VARCHAR(30) NOT NULL,
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
            REFERENCES roles (id)
);

CREATE TABLE articles
(
    id            MEDIUMINT UNSIGNED AUTO_INCREMENT,
    title         VARCHAR(50)       NOT NULL,
    description   TEXT              NOT NULL,
    texte         TEXT              NOT NULL,
    image         VARCHAR(50),
    sport_id TINYINT UNSIGNED  NOT NULL,
    author_id     INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT articles_to_sports
        FOREIGN KEY (sport_id)
            REFERENCES sports (id),
    CONSTRAINT articles_to_users
        FOREIGN KEY (author_id)
            REFERENCES users (id)
);


CREATE TABLE comments
(
    id            INT UNSIGNED AUTO_INCREMENT,
    author_name   VARCHAR(50) NOT NULL,
    comment_text  TEXT        NOT NULL,
    created_at    TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    article_id     MEDIUMINT UNSIGNED,
    PRIMARY KEY (id),
    CONSTRAINT comments_to_articles
        FOREIGN KEY (article_id)
            REFERENCES articles (id)
);

CREATE TABLE favorites
(
    user_id    INT UNSIGNED,
    sports_id  TINYINT UNSIGNED,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, sports_id),
    CONSTRAINT favorites_to_users
        FOREIGN KEY (user_id)
            REFERENCES users (id),
    CONSTRAINT favorites_to_sports
        FOREIGN KEY (sports_id)
            REFERENCES sports (id)
);

CREATE TABLE medias
(
    id  	SMALLINT UNSIGNED AUTO_INCREMENT,
    title 	VARCHAR(50) NOT NULL,
    url  	VARCHAR(150),
    PRIMARY KEY (id)
);

-- insertion des roles (utilisateur, auteur)
INSERT INTO roles (role_name) VALUES ('membre'), ('admin');


-- Insert des sports
INSERT INTO sports
VALUES ('Bascketball'), ('Football'), ('Volleyball'), ('Rugby'), ('Judo'),('Karate'),('Handball'),('Formule 1'),('Moto GP');

-- insert d'un user
INSERT INTO users (user_name, user_email, user_password, role_id) VALUES
('joe user', 'joe@mail.com', 'AZERTY', 2);


-- Insert d'une article pour tester la page
INSERT INTO articles(
    title,
    description,
    texte,
    image,
    sport_id,
    author_id,
    created_at)
VALUES (
           'Foot',
           'Le retour du football en Europe',
           'Arrivé il y a moins d’un an en provenance de l’Atletico de Madrid, Lucas Hernandez avait tout pour devenir la nouvelle coqueluche de l’Allianz Arena. Le champion du monde 2018, capable d’évoluer à gauche ou en position de défenseur central gauche, arrivait au Bayern contre un chèque de 80 M€, soit le plus gros transfert de l’histoire du club allemand. Mais une vilaine blessure à la cheville qui l’a éloigné des terrains pendant trois mois et l’éclosion du phénomène Alphonso Davies ont rapidement transformé le rêve Bayern d’Hernandez en véritable cauchemar. Au final depuis son arrivée, le n°21 n’a disputé que 16 matches dont seulement 7 en tant que titulaire.',
           'foot.jpg',
           2,
           2,
           '2020-02-01'
       );


