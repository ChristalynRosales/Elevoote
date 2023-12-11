<?php

class DatabaseConnection {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'votingsystem';
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create an instance of the DatabaseConnection class
$databaseConnection = new DatabaseConnection();

// Get the PDO connection object
$conn = $databaseConnection->getConnection();

// Now, you can use $conn for your database queries

?>
