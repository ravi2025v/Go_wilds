<?php
require_once 'admin/includes/db.php';
$res = $conn->query("SELECT * FROM tour_bookings ORDER BY id DESC LIMIT 5");
echo "<h2>Recent Tour Bookings</h2>";
echo "<table border='1'><tr><th>ID</th><th>User ID</th><th>Name</th><th>Tour ID</th><th>Created At</th></tr>";
while($row = $res->fetch_assoc()) {
    echo "<tr><td>{$row['id']}</td><td>{$row['user_id']}</td><td>{$row['customer_name']}</td><td>{$row['tour_id']}</td><td>{$row['created_at']}</td></tr>";
}
echo "</table>";

session_start();
echo "<h2>Current Session</h2>";
echo "User ID in session: " . ($_SESSION['user_id'] ?? 'Not set');
?>
