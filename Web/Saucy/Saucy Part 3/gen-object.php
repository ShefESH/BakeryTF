<?php

class SecretRecipe
{
    public $encrypted = False;
    public $title = "Test";
    public $contents = "Test";
    public $id = 1;
}

$class_instance = new SecretRecipe;

$serialised_object = serialize($class_instance);

echo $serialised_object . "\n";

?>