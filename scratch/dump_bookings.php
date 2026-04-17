<?php
require_once 'admin/includes/db.php';
$res = $conn->query("SELECT id, hotel_type, hotel_price, total_price, tour_id FROM tour_bookings ORDER BY id DESC LIMIT 10");
header('Content-Type: application/json');
$rows = [];
while($row = $res->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows, JSON_PRETTY_PRINT);
