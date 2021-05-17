<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Database
{
    //adapted from https://stackoverflow.com/questions/32188985/php-make-other-functions-access-the-conn-variable-inside-my-database-connecti

    /** TRUE if static variables have been initialized. FALSE otherwise
    */
    private static $init = FALSE;
    /** The mysqli connection object
    */
    public static $conn;
    /** initializes the static class variables. Only runs initialization once.
    * does not return anything.
    */
    public static function initialize()
    {
        include 'conf_secret.php';

        // if (self::$init===TRUE)return;
        self::$init = TRUE;
        self::$conn = new mysqli($server, $username, $password, $database);

        if (self::$conn->connect_error) {
            error_log("Connection failed: " . self::$conn->connect_error);
        }

        else {
            error_log("Connected");
        }
    }
}

?>