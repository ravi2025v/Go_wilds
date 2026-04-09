<?php
// review_submit.php
session_start();
require_once 'admin/includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to write a review.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $tour_id = intval($_POST['tour_id']);
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment']);

    if ($tour_id <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide rating and comment.']);
        exit;
    }

    $sql = "INSERT INTO reviews (user_id, tour_id, rating, comment, status) VALUES ($user_id, $tour_id, $rating, '$comment', 'pending')";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted! It will be visible after admin approval.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
    }
}
?>
