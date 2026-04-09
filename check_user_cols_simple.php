<?php 
require_once 'admin/includes/db.php';
$res = $conn->query("DESCRIBE users");
$cols = [];
while($row = $res->fetch_assoc()) $cols[] = $row['Field'];
echo implode(',', $cols);
?>
