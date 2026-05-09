<?php
header('Content-Type: application/json');
session_name("GoWilds_Session");
session_start();
require_once 'admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Session expired. Please login.']);
        exit;
    }

    $booking_id = intval($_POST['booking_id']);
    
    // Fetch user's current session cart
    $activity_total = 0;
    $activity_names = [];
    if (isset($_SESSION['activity_cart']) && !empty($_SESSION['activity_cart'])) {
        foreach ($_SESSION['activity_cart'] as $act) {
            $activity_total += floatval($act['price']);
            $activity_names[] = $act['title'];
        }
    }
    $activities_summary = implode(', ', $activity_names);

    // Fetch existing booking details from DB
    $b_query = $conn->query("SELECT * FROM tour_bookings WHERE id = $booking_id AND user_id = " . $_SESSION['user_id']);
    if (!$b_query || $b_query->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Booking not found or access denied']);
        exit;
    }
    $b = $b_query->fetch_assoc();

    // RECALCULATE FINAL PRICE (Professional Standard)
    // Formula: (HotelPrice * (Adults + Children)) + BookingFee(50) + ServiceFee(20 * AllPax) + Activities
    $adults = intval($b['adults']);
    $children = intval($b['children']);
    $infants = intval($b['infants']);
    $pax_with_infants = $adults + $children + $infants;
    
    $base_hotel_total = floatval($b['hotel_price']) * ($adults + $children);
    $fees_total = 50 + (20 * $pax_with_infants);
    
    $new_total_price = $base_hotel_total + $fees_total + $activity_total;

    // Update Database
    $update = $conn->query("UPDATE tour_bookings SET 
                           selected_activities = '$activities_summary', 
                           total_price = $new_total_price 
                           WHERE id = $booking_id");

    if ($update) {
        unset($_SESSION['activity_cart']); // Clear after save
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
