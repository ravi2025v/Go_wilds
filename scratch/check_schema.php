<?php
require_once 'admin/includes/db.php';
$res = $conn->query("DESCRIBE tours");
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
