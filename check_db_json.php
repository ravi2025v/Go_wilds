<?php
require_once 'admin/includes/db.php';
$result = $conn->query("DESCRIBE tours");
$output = [];
while($row = $result->fetch_assoc()) {
    $output[] = $row;
}
file_put_contents('db_schema.json', json_encode($output, JSON_PRETTY_PRINT));
echo "Schema written to db_schema.json\n";
?>
