<?php
require_once 'admin/includes/db.php';
$res = $conn->query("SELECT * FROM tour_bookings LIMIT 10");
$out = "BOOKINGS DATA:\n";
while($row = $res->fetch_assoc()) {
    $out .= "ID: " . $row['id'] . " | UserID: " . $row['user_id'] . "\n";
}

$res2 = $conn->query("SELECT id, name FROM users LIMIT 10");
$out .= "\nUSERS DATA:\n";
while($row = $res2->fetch_assoc()) {
    $out .= "ID: " . $row['id'] . " | Name: " . $row['name'] . "\n";
}

file_put_contents('scratch/check_ids.txt', $out);
echo "Done";
?>
