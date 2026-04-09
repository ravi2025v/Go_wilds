<?php
require_once 'admin/includes/db.php';
$sql = "ALTER TABLE tours 
        ADD COLUMN min_people INT DEFAULT 1 AFTER max_people,
        ADD COLUMN min_age VARCHAR(50) DEFAULT '12+' AFTER min_people,
        ADD COLUMN language VARCHAR(100) DEFAULT 'English' AFTER min_age";

if($conn->query($sql)) {
    echo "Columns added successfully\n";
} else {
    echo "Error adding columns: " . $conn->error . "\n";
}
?>
