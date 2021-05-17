DROP DATABASE if exists recipes;
CREATE DATABASE recipes;

use recipes;

CREATE TABLE recipes (recipe_id int NOT NULL AUTO_INCREMENT, recipe_title varchar(100), recipe_text varchar(1000), PRIMARY KEY (recipe_id));

INSERT INTO recipes (recipe_title, recipe_text) VALUES ("Secret Recipe - Shh!", '1) Add c00k13s to a bowl 2) Mix in some syntactic sUg4R 3) Take two bYT3s');
INSERT INTO recipes (recipe_title, recipe_text) VALUES ("Mayonnaise", '1) Mix some eggs in a bowl? 2) Idk how to make mayonnaise');
INSERT INTO recipes (recipe_title, recipe_text) VALUES ("Ketchup", '1) Mix some ketchup in a bowl 2) Eat it 3) Profit');

CREATE TABLE recipe_strings (id int NOT NULL AUTO_INCREMENT, recipe_recipe varchar(1000), PRIMARY KEY (id));

INSERT INTO recipe_strings (recipe_recipe) VALUES ('O:12:"SecretRecipe":3:{s:5:"title";s:4:"Test";s:8:"contents";s:4:"Test";s:2:"id";i:2;}');
INSERT INTO recipe_strings (recipe_recipe) VALUES ('O:12:"SecretRecipe":3:{s:5:"title";s:4:"Test";s:8:"contents";s:4:"Test";s:2:"id";i:3;}');
INSERT INTO recipe_strings (recipe_recipe) VALUES ('O:12:"SecretRecipe":3:{s:5:"title";s:4:"Test";s:8:"contents";s:4:"Test";s:2:"id";i:1;}');