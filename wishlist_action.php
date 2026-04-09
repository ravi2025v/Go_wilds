<?php
session_start();
require_once 'admin/includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Please login to add to wishlist");
    exit;
}

$user_id = $_SESSION['user_id'];
$tour_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($tour_id > 0) {
    // Check if already in wishlist
    $check = $conn->query("SELECT id FROM wishlist WHERE user_id = $user_id AND tour_id = $tour_id");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO wishlist (user_id, tour_id) VALUES ($user_id, $tour_id)");
        $msg = "Added to wishlist!";
    } else {
        $msg = "Already in your wishlist.";
    }
    header("Location: tour-details.php?id=$tour_id&msg=$msg");
} else {
    header("Location: tour.php");
}
?>
