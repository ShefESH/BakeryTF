<html>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel='stylesheet' href='styles.css'>
<div class='get-recipe'>
<?php

include 'database.php';

if(isset($_GET["recipe_recipe"])) {
    //let people submit their own recipes to see how they're displayed on page

    $recipe_string = $_GET["recipe_recipe"];

    //check they're submitting a Secret Recipe class
    if(strpos($recipe_string, 'O:12:"SecretRecipe"') !== false) {
        //insert into recipe_strings database
        error_log("Inserting");

        Database::initialize();

        $stmt = Database::$conn->prepare("INSERT INTO recipe_strings (recipe_recipe) VALUES (?)");

        // execute statement with submitted string

        $stmt->bind_param("s", $recipe_string);

        $stmt->execute();

        // close statement
        $stmt->close();

        // get ID of last item
        $id = Database::$conn->insert_id;

        echo("Thanks for submitting! Your ID: ". $id);
    }
}