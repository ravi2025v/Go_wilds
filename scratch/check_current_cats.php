<?php
require_once 'admin/includes/db.php';
$res = $conn->query("SELECT DISTINCT category FROM tours");
while($row = $res->fetch_assoc()) {
    echo $row['category'] . "\n";
}
?>
