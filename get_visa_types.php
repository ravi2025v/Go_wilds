<?php
include 'admin/includes/db.php';

if (isset($_GET['country_id'])) {
    $country_id = intval($_GET['country_id']);
    $query = "SELECT * FROM visa_services WHERE country_id = $country_id AND status = 'active' ORDER BY price ASC";
    $result = $conn->query($query);
    
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($services);
}
?>
