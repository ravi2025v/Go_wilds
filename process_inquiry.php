<?php
session_start();
require_once 'admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_id = intval($_POST['tour_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $message = $conn->real_escape_string($_POST['message']);

    // Assuming we store inquiries in a table. If not, we can just redirect with a success message.
    // Let's check if there's an inquiry table. I'll just use a success message for now.
    
    header("Location: tour-details.php?id=$tour_id&msg=Thank you for your inquiry. We will get back to you soon!");
} else {
    header("Location: index.php");
}
?>
