<?php
session_name("GoWilds_Session");
session_start();
require_once 'admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?msg=Please login to book a tour");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $tour_id = intval($_POST['tour_id']);
    $tour_date = $conn->real_escape_string($_POST['tour_date']);
    $booking_time = $conn->real_escape_string($_POST['booking_time']);
    $adults = intval($_POST['adults']);
    $children = intval($_POST['children']);
    $infants = intval($_POST['infants']);
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $customer_email = $conn->real_escape_string($_POST['customer_email']);
    $customer_phone = $conn->real_escape_string($_POST['customer_phone']);

    // Get tour price data
    $tour_res = $conn->query("SELECT * FROM tours WHERE id = $tour_id");
    if (!$tour_res || $tour_res->num_rows == 0) {
        header("Location: tour.php?msg=Tour not found");
        exit;
    }
    $tour = $tour_res->fetch_assoc();

    // Hotel logic and Base Price
    $basePrice = $tour['price'];
    $hotel_type = $conn->real_escape_string($_POST['hotel_type']);
    $upgrade_price = 0;
    switch ($hotel_type) {
        case '3*': $upgrade_price = $tour['price_3star']; break;
        case '4*': $upgrade_price = $tour['price_4star']; break;
        case '5*': $upgrade_price = $tour['price_5star']; break;
        case 'Camps': $upgrade_price = $tour['price_camps']; break;
        case 'Homestay': $upgrade_price = $tour['price_homestay']; break;
        default: $upgrade_price = 0; $hotel_type = 'Budget'; break;
    }

    // Save total per-pax price (base + upgrade)
    $hotel_price = $basePrice + $upgrade_price;

    // Calculate Total Booking Price
    $totalPeople = $adults + $children;
    $total_price = ($hotel_price * $totalPeople) + 50 + (20 * ($totalPeople + $infants));

    // Convert date to Y-m-d if it's in dd/mm/yyyy or similar
    $formatted_tour_date = date('Y-m-d', strtotime($tour_date));

    $sql = "INSERT INTO tour_bookings (tour_id, user_id, customer_name, customer_email, customer_phone, tour_date, booking_time, adults, children, infants, hotel_price, hotel_type, total_price, status) 
            VALUES ($tour_id, $user_id, '$customer_name', '$customer_email', '$customer_phone', '$formatted_tour_date', '$booking_time', $adults, $children, $infants, $hotel_price, '$hotel_type', $total_price, 'pending')";

    if ($conn->query($sql)) {
        header("Location: my-bookings.php?msg=Booking successful! Order is pending confirmation.");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: index.php");
}
?>
