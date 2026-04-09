<?php
require_once 'admin/includes/db.php';
$conn->query("ALTER TABLE tour_bookings ADD COLUMN user_id INT(11) AFTER tour_id");
echo "Column added successfully";
?>
