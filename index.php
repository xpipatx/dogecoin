<?php
require_once 'dbcontroller.php';

$db = new DBController();

try {
    // This is the problematic query that's causing the error
    // The 'updated_at' column doesn't exist in the hm18_fee table
    $query = "UPDATE hm18_fee SET status = ?, updated_at = NOW() WHERE id = ?";
    $params = array('active', 1);
    
    // This will fail because 'updated_at' column doesn't exist
    $result = $db->execute($query, $params);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Solution 1: Remove the updated_at column from the query
    echo "\n=== Solution 1: Remove updated_at column ===\n";
    try {
        $fixed_query = "UPDATE hm18_fee SET status = ? WHERE id = ?";
        $result = $db->execute($fixed_query, $params);
        echo "Query executed successfully!\n";
    } catch (Exception $e2) {
        echo "Still error: " . $e2->getMessage() . "\n";
    }
    
    // Solution 2: Add the updated_at column to the database
    echo "\n=== Solution 2: Add updated_at column to database ===\n";
    echo "Run this SQL command in your database:\n";
    echo "ALTER TABLE hm18_fee ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;\n";
}
?>