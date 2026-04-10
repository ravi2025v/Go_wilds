<?php
require_once 'admin/includes/db.php';

// Check if description column exists
$check_desc = $conn->query("SHOW COLUMNS FROM tours LIKE 'description'");
if ($check_desc->num_rows == 0) {
    $conn->query("ALTER TABLE tours ADD COLUMN description TEXT AFTER duration");
    echo "Added description column.\n";
}

// Check if more_info column exists
$check_info = $conn->query("SHOW COLUMNS FROM tours LIKE 'more_info'");
if ($check_info->num_rows == 0) {
    $conn->query("ALTER TABLE tours ADD COLUMN more_info TEXT AFTER description");
    echo "Added more_info column.\n";
}

echo "Database update check complete.\n";
?>
