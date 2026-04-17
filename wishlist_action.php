<?php
// wishlist_action.php
session_name("GoWilds_Session");
session_start();
require_once 'admin/includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

$user_id = $_SESSION['user_id'];
$tour_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($tour_id > 0) {
    // Check if already in wishlist
    $check = $conn->query("SELECT id FROM wishlist WHERE user_id = $user_id AND tour_id = $tour_id");
    
    if ($check->num_rows == 0) {
        // Add to wishlist
        $conn->query("INSERT INTO wishlist (user_id, tour_id) VALUES ($user_id, $tour_id)");
        echo json_encode(['status' => 'added', 'message' => 'Added to wishlist!']);
    } else {
        // Remove from wishlist (Toggle behavior)
        $conn->query("DELETE FROM wishlist WHERE user_id = $user_id AND tour_id = $tour_id");
        echo json_encode(['status' => 'removed', 'message' => 'Removed from wishlist!']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid tour ID']);
}
?>
