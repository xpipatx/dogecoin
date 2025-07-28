-- Fix for the missing 'updated_at' column in hm18_fee table
-- Run this script in your MySQL database

-- First, let's check the current structure of the table
DESCRIBE hm18_fee;

-- Add the missing updated_at column
ALTER TABLE hm18_fee ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Verify the column was added
DESCRIBE hm18_fee;

-- Optional: If you want to add created_at column as well (common practice)
-- ALTER TABLE hm18_fee ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Show the updated table structure
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'hm18_fee' 
AND TABLE_SCHEMA = DATABASE();