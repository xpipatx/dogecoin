<?php
class DBController {
    private $host = "localhost";
    private $user = "your_username";
    private $password = "your_password";
    private $database = "your_database";
    private $conn;

    function __construct() {
        $this->conn = $this->connectDB();
    }

    function connectDB() {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $conn;
    }

    function execute($query, $params = array()) {
        try {
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                throw new Exception("SQL Prepare Error: " . $this->conn->error);
            }
            
            // Bind parameters if provided
            if (!empty($params)) {
                $types = str_repeat('s', count($params)); // Assuming all strings for now
                $stmt->bind_param($types, ...$params);
            }
            
            // Execute the statement
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("SQL Execute Error: " . $stmt->error);
            }
            
            $stmt->close();
            return $result;
            
        } catch (Exception $e) {
            throw new Exception("SQL Prepare Error: " . $e->getMessage());
        }
    }

    function query($query) {
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    function numRows($query) {
        $result = mysqli_query($this->conn, $query);
        $rowcount = mysqli_num_rows($result);
        return $rowcount;
    }
}
?>