<?php
require_once 'admin/includes/db.php';
$conn->query("ALTER TABLE tour_bookings ADD COLUMN booking_time VARCHAR(50) DEFAULT NULL AFTER booking_date");
$conn->query("ALTER TABLE tour_bookings ADD COLUMN hotel_type VARCHAR(100) DEFAULT NULL AFTER hotel_price");
echo "Columns added successfully";
?>
