# Fix for "Unknown column 'updated_at' in 'field list'" Error

## Problem
The error occurs because your SQL query is trying to update an `updated_at` column that doesn't exist in the `hm18_fee` table.

## Error Details
- **File**: `/home/kaewkase/domains/kaewkaset.com/public_html/hm18/dbcontroller.php:65`
- **Error**: `SQL Prepare Error: Unknown column 'updated_at' in 'field list'`
- **Table**: `hm18_fee`

## Solutions

### Solution 1: Add the missing column to the database (Recommended)

Run this SQL command in your MySQL database:

```sql
ALTER TABLE hm18_fee ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
```

### Solution 2: Remove the updated_at column from your query

If you don't need the `updated_at` column, modify your query in `index.php`:

**Before:**
```sql
UPDATE hm18_fee SET status = ?, updated_at = NOW() WHERE id = ?
```

**After:**
```sql
UPDATE hm18_fee SET status = ? WHERE id = ?
```

### Solution 3: Use the automated fix script

1. Update the database credentials in `dbcontroller.php`
2. Run the `check_and_fix.php` script:
   ```bash
   php check_and_fix.php
   ```

## Files Created

1. **`dbcontroller.php`** - Database controller class with proper error handling
2. **`index.php`** - Example showing the problematic query and solutions
3. **`fix_database.sql`** - SQL script to add the missing column
4. **`check_and_fix.php`** - Automated script to check and fix the database structure
5. **`README_fix_instructions.md`** - This file with detailed instructions

## Database Configuration

Before running any scripts, update the database credentials in `dbcontroller.php`:

```php
private $host = "your_host";
private $user = "your_username";
private $password = "your_password";
private $database = "your_database";
```

## Verification

After applying the fix, you can verify the table structure:

```sql
DESCRIBE hm18_fee;
```

You should see the `updated_at` column in the output.

## Common Issues

1. **Permission denied**: Make sure your database user has ALTER privileges
2. **Connection failed**: Check your database credentials
3. **Table doesn't exist**: Verify the table name is correct

## Additional Notes

- The `updated_at` column will automatically update to the current timestamp whenever a row is modified
- Consider adding a `created_at` column as well for better record keeping
- Always backup your database before making schema changes