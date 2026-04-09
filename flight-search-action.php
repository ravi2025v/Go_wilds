<?php
require_once 'admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trip_type = isset($_POST['trip_type']) ? $conn->real_escape_string($_POST['trip_type']) : 'One way';
    $origin = isset($_POST['origin']) ? $conn->real_escape_string($_POST['origin']) : '';
    $destination = isset($_POST['destination']) ? $conn->real_escape_string($_POST['destination']) : '';
    $departure_date = isset($_POST['departure_date']) ? $conn->real_escape_string($_POST['departure_date']) : '';
    $adults = isset($_POST['adults']) ? intval($_POST['adults']) : 1;
    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;
    $infants = isset($_POST['infants']) ? intval($_POST['infants']) : 0;
    $special_fare = isset($_POST['special_fare']) ? $conn->real_escape_string($_POST['special_fare']) : 'Regular';
    $travel_class = isset($_POST['travel_class']) ? $conn->real_escape_string($_POST['travel_class']) : 'Economy';

    // Insert user search log into DB for the admin panel tracking
    $sql = "INSERT INTO flight_searches (trip_type, origin, destination, departure_date, adults, children, infants, travel_class, special_fare) 
            VALUES ('$trip_type', '$origin', '$destination', '$departure_date', $adults, $children, $infants, '$travel_class', '$special_fare')";
    
    $conn->query($sql);

    // After saving the search query, redirect to a search results page
    header("Location: search-results.php?origin=" . urlencode($origin) . "&destination=" . urlencode($destination) . "&departure=" . urlencode($departure_date) . "&travellers=" . urlencode($adults));
    exit();
}
?>
