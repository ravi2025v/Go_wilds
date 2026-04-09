<?php
require_once 'admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $tour_id = intval($_POST['tour_id'] ?? 0);
    $user_ip = $_SERVER['REMOTE_ADDR'];

    if ($action === 'toggle_wishlist' && $tour_id > 0) {
        $check = $conn->query("SELECT id FROM wishlist WHERE tour_id = $tour_id AND user_ip = '$user_ip'");
        
        if ($check->num_rows > 0) {
            $conn->query("DELETE FROM wishlist WHERE tour_id = $tour_id AND user_ip = '$user_ip'");
            echo json_encode(['status' => 'removed']);
        } else {
            $conn->query("INSERT INTO wishlist (tour_id, user_ip) VALUES ($tour_id, '$user_ip')");
            echo json_encode(['status' => 'added']);
        }
    }

    if ($action === 'submit_review' && $tour_id > 0) {
        $name = $conn->real_escape_string($_POST['name']);
        $rating = intval($_POST['rating']);
        $comment = $conn->real_escape_string($_POST['comment']);

        $sql = "INSERT INTO reviews (tour_id, user_name, rating, comment) VALUES ($tour_id, '$name', $rating, '$comment')";
        if ($conn->query($sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error submitting review.']);
        }
    }
}
?>
