<?php
require_once 'admin/includes/db.php';
$result = $conn->query("DESCRIBE tours");
while($row = $result->fetch_assoc()) {
    echo "| " . str_pad($row['Field'], 20) . " | " . str_pad($row['Type'], 20) . " |\n";
}
?>
