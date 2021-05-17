<html>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel='stylesheet' href='styles.css'>
<div class='get-recipe'>
<?php

include 'database.php';

class SecretRecipe
{
    public $encrypted = True;
    public $title = "";
    public $contents = "";

    public function __construct($id) {
        $this->id = $id;

        error_log("Constructed! ID: ". $this->id);
    }

    public function get_from_db() {
        Database::initialize();

        $stmt = Database::$conn->prepare("SELECT recipe_title, recipe_text FROM recipes WHERE recipe_id = ?");

        // execute statement with ID variable

        $stmt->bind_param("i", $this->id);

        $stmt->execute();

        $stmt->bind_result($recipe_title, $recipe_text);

        // output results to page

        while ($stmt->fetch()) {
            if ($this->encrypted) {
                echo("<h3>Title</h3>");
                echo("<p>" . $recipe_title . "</p>");
                echo("<h3>Recipe</h3>");
                echo("<p>" . hash('sha256', $recipe_text) . "</p>");
            }
            else {
                echo("<h3>Title</h3>");
                echo("<p>" . $recipe_title . "</p>");
                echo("<h3>Recipe</h3>");
                echo("<p>" . $recipe_text . "</p>");
            }
        }
    
        // close statement
        $stmt->close();
    }

    public function __wakeup() {
        //grab from DB when deserialised
        error_log("Woken up - getting from DB");

        $this->get_from_db();
    }
}

//show contents of recipe file
if ($_GET['recipe']){
    if (is_file("recipes/" . $_GET["recipe"]) && file_exists("recipes/" . $_GET["recipe"])) {
        show_source("recipes/" . $_GET["recipe"]); 
    } else {
        echo('<p>Recipe not found. Try again!</p>');
    }
} elseif ($_GET['id']){
    //getting recipe from DB by ID

    $recipe = new SecretRecipe($_GET['id']);

    $recipe->get_from_db();
    
} elseif ($_GET['string_id']){
    //getting recipe string from DB by ID, constructing recipe

    error_log("Getting recipe string by ID");

    error_log($_GET['string_id']);

    $id = intval($_GET['string_id']);

    //get recipe string from DB based on ID
    Database::initialize();

    $stmt = Database::$conn->prepare("SELECT recipe_recipe FROM recipe_strings WHERE id = ?");

    // execute statement with ID variable

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->bind_result($recipe_recipe);

    //get results

    while ($stmt->fetch()) {

        error_log("Recipe string from DB: " . $recipe_recipe);

        // deserialise recipe string

        unserialize($recipe_recipe);
    }

    // close statement
    $stmt->close();
} else {
    //no params given
    echo('Please provide either a recipe name or recipe ID.');
}

?>
</div>
<!-- DEVS: secret recipe is ID 1 in DB -->

</body>
</html>