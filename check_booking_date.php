<?php
require_once 'admin/includes/db.php';
$res = $conn->query("DESCRIBE tour_bookings");
while($row = $res->fetch_assoc()) {
    echo "Field: " . $row['Field'] . " | Type: " . $row['Type'] . "<br>";
}
echo "<hr>";
$data_res = $conn->query("SELECT booking_date FROM tour_bookings LIMIT 5");
while($row = $data_res->fetch_assoc()) {
    echo "Real value in DB: [" . $row['booking_date'] . "]<br>";
}
?>
