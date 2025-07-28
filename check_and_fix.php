<?php
require_once 'dbcontroller.php';

class DatabaseFixer {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function checkColumnExists($table, $column) {
        $query = "SHOW COLUMNS FROM $table LIKE '$column'";
        $result = $this->db->query($query);
        return mysqli_num_rows($result) > 0;
    }
    
    public function addUpdatedAtColumn($table) {
        if (!$this->checkColumnExists($table, 'updated_at')) {
            $query = "ALTER TABLE $table ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
            $result = $this->db->query($query);
            
            if ($result) {
                echo "✓ Successfully added 'updated_at' column to $table table\n";
                return true;
            } else {
                echo "✗ Failed to add 'updated_at' column to $table table\n";
                return false;
            }
        } else {
            echo "✓ Column 'updated_at' already exists in $table table\n";
            return true;
        }
    }
    
    public function showTableStructure($table) {
        echo "\n=== Current structure of $table table ===\n";
        $query = "DESCRIBE $table";
        $result = $this->db->query($query);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo sprintf("%-15s %-15s %-10s %-10s %-10s %-10s\n", 
                    $row['Field'], 
                    $row['Type'], 
                    $row['Null'], 
                    $row['Key'], 
                    $row['Default'], 
                    $row['Extra']
                );
            }
        }
        echo "\n";
    }
}

// Usage example
try {
    $db = new DBController();
    $fixer = new DatabaseFixer($db);
    
    echo "=== Database Structure Check ===\n";
    $fixer->showTableStructure('hm18_fee');
    
    echo "=== Fixing missing updated_at column ===\n";
    $fixer->addUpdatedAtColumn('hm18_fee');
    
    echo "\n=== Updated table structure ===\n";
    $fixer->showTableStructure('hm18_fee');
    
    echo "=== Testing the original query ===\n";
    $query = "UPDATE hm18_fee SET status = ?, updated_at = NOW() WHERE id = ?";
    $params = array('active', 1);
    
    try {
        $result = $db->execute($query, $params);
        echo "✓ Query executed successfully!\n";
    } catch (Exception $e) {
        echo "✗ Query still failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>