<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "e_life";
    protected $conn; // Accessible by child classes

    public function __construct()
    {
        $this->Connection(); // Automatically establish connection
    }

    // Your original connection method
    public function Connection()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            echo "Error Message: " . $error->getMessage();
        }
        return $this->conn;
    }

    // Fetch a single record
    public function fetch($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch multiple records
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert, Update, Delete queries
    public function execute($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
    public function insert($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $this->conn->lastInsertId(); // Return the last inserted ID
    }

    // Get the last inserted ID
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }
}
