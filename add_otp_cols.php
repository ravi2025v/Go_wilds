<?php
require_once 'admin/includes/db.php';

$sql = "ALTER TABLE users 
        ADD COLUMN phone VARCHAR(20) DEFAULT NULL,
        ADD COLUMN phone_verified TINYINT(1) DEFAULT 0,
        ADD COLUMN otp_code VARCHAR(10) DEFAULT NULL,
        ADD COLUMN otp_expiry DATETIME DEFAULT NULL";

if($conn->query($sql)) {
    echo "OTP columns added successfully to users table.\n";
} else {
    // If columns already exist, it might error, so we check for that
    if (strpos($conn->error, "Duplicate column name") !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error adding columns: " . $conn->error . "\n";
    }
}
?>
