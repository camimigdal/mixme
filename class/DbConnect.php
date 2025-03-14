<?php

class DbConnect {
 
    private $conn;
 
    function __construct() {        
    } 
    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once dirname(__FILE__) . '/Config.php';

        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
        // Check for database connection error
        if (mysqli_connect_errno()) {
            echo "Error de conexión a la base de datos: " . mysqli_connect_error();
        }
 
        // returing connection resource
        $this->conn->set_charset("utf8");
        return $this->conn;

    }
 
}
?>