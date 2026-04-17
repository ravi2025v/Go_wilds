<?php
require_once 'admin/includes/db.php';
$res1 = $conn->query("DESCRIBE wishlist");
$out = "WISHLIST TABLE:\n";
while($row = $res1->fetch_assoc()) $out .= $row['Field'] . " - " . $row['Type'] . "\n";

$res2 = $conn->query("DESCRIBE tours");
$out .= "\nTOURS TABLE:\n";
while($row = $res2->fetch_assoc()) $out .= $row['Field'] . " - " . $row['Type'] . "\n";

file_put_contents('scratch/db_check.txt', $out);
echo "Done";
