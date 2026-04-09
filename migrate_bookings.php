<?php
require_once 'admin/includes/db.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $email = $_SESSION['user_email'];
    $conn->query("UPDATE tour_bookings SET user_id = $uid WHERE user_id IS NULL AND customer_email = '$email'");
    echo "Migration Done!";
} else {
    echo "User not logged in";
}
?>
