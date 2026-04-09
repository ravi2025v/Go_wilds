<?php
require_once 'admin/includes/db.php';
// Match all tour_bookings where user_id is NULL or 0 via the email address
$conn->query("UPDATE tour_bookings tb JOIN users u ON tb.customer_email = u.email SET tb.user_id = u.id WHERE tb.user_id IS NULL OR tb.user_id = 0");
echo "Association migration completed!";
?>
