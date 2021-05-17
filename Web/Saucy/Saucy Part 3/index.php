<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel='stylesheet' href='styles.css'>
<div class='content'>
  <h1>Get Your Recipes Here!</h1>
  <h2>We have the best sauces, cereals, and jams</h2>
  <p>Try "horseradish-sauce.txt"!</p>
  <form method="get" action="/get-recipe.php">
    <label for="recipe">Recipe Name</label>
    <input name="recipe" id="recipe">
    <p>Or...</p>
    <label for="id">Get Secret Recipe by ID</label>
    <input name="id" id="id">
    <p>Or...</p>
    <label for="id">Construct Recipe from String in Database</label>
    <input name="string_id" id="string_id">
    <br><br>
    <input type="submit" value="Get Recipe" class='btn btn-outline-secondary'>
  </form>
  <!-- DEVS: See code for retrieving recipes in get-recipe.php -->
  <!-- DEVS: See code for submitting new recipes in create-recipe.php -->
</div>