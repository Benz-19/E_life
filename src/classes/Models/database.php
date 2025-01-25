<?php

class Database
{
    private $host = "localhost";
    private   $username = "root";
    private  $password = "";
    private  $dbname = "e_life";
    private  $conn;

    public function Connection()
    {
        try {
            $dsn = "mysql:host={$this->host}; dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $error) {
            echo "Error Message: " . $error->getMessage();
        }

        return $this->conn;
    }
}
