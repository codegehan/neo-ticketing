<?php

class DatabaseConnector
{
    private $host = 'localhost';
    private $port = 3308;
    private $db = 'ticketing';
    private $user = 'codegehan';
    private $pass = '!@#Admin123*';
    private $charset = 'utf8mb4';
    public $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function closeConnection()
    {
        $this->pdo = null;
    }
    public function query($sql, $params = [])
    {
        if ($this->pdo === null) {
            $this->connect();  // Call a method to reconnect if necessary
        }

        try{
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            if ($result) {
                $this->pdo->commit();
                return $stmt;  // Return statement for further operations (e.g., fetch)
            } else {
                throw new \PDOException("Failed to execute request.");
            }
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log("Database Error: " . $e->getMessage());  // Log the error for debugging
            return false;
        }
        finally {
            $this->closeConnection();
        }
        
    }
    public function fetch($sql, $params = [])
    {
        if ($this->pdo === null) {
            $this->connect();  // Call a method to reconnect if necessary
        }

        try {
            $stmt = $this->query($sql, $params);
            return $stmt->fetch();
        } finally {
            $this->closeConnection();
        }
    }

    public function fetchAll($sql, $params = [])
    {
        if ($this->pdo === null) {
            $this->connect();  // Call a method to reconnect if necessary
        }
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->fetchAll();
        } finally {
            $this->closeConnection();
        }
    }
}
