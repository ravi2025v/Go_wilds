<?php
include 'admin/includes/db.php';

echo "<h2>Inserting Sample Visa Types...</h2>";

// Get country IDs
$countries = [];
$res = $conn->query("SELECT id, country_name FROM visa_countries");
while($row = $res->fetch_assoc()) {
    $countries[$row['country_name']] = $row['id'];
}

$visa_data = [
    'UAE' => [
        ['name' => '30 Days Tourist Visa (Single Entry)', 'price' => 6500, 'time' => '3-5 Days', 'validity' => '58 Days'],
        ['name' => '60 Days Tourist Visa (Single Entry)', 'price' => 12500, 'time' => '3-5 Days', 'validity' => '58 Days'],
        ['name' => '96 Hours Transit Visa', 'price' => 4500, 'time' => '48 Hours', 'validity' => '30 Days']
    ],
    'USA' => [
        ['name' => 'B1/B2 Visitor Visa', 'price' => 15500, 'time' => '10-15 Days', 'validity' => '10 Years']
    ],
    'UK' => [
        ['name' => 'Standard Visitor Visa (6 Months)', 'price' => 11000, 'time' => '15 Working Days', 'validity' => '6 Months']
    ],
    'France' => [
        ['name' => 'Schengen Tourist Visa', 'price' => 8500, 'time' => '10-15 Days', 'validity' => '90 Days']
    ],
    'Germany' => [
        ['name' => 'Schengen Tourist Visa', 'price' => 8500, 'time' => '10-15 Days', 'validity' => '90 Days']
    ],
    'Japan' => [
        ['name' => 'Tourist Visa (Single Entry)', 'price' => 2500, 'time' => '4-5 Working Days', 'validity' => '90 Days']
    ],
    'Australia' => [
        ['name' => 'Visitor Visa (Subclass 600)', 'price' => 9500, 'time' => '20-25 Days', 'validity' => '1 Year']
    ],
    'India' => [
        ['name' => 'e-Tourist Visa (30 Days)', 'price' => 2100, 'time' => '72 Hours', 'validity' => '30 Days'],
        ['name' => 'e-Tourist Visa (1 Year)', 'price' => 4200, 'time' => '72 Hours', 'validity' => '1 Year']
    ]
];

foreach ($visa_data as $country_name => $services) {
    if (isset($countries[$country_name])) {
        $c_id = $countries[$country_name];
        foreach ($services as $s) {
            $name = $s['name'];
            $price = $s['price'];
            $time = $s['time'];
            $validity = $s['validity'];
            
            // Check if already exists
            $check = $conn->query("SELECT id FROM visa_services WHERE country_id=$c_id AND visa_name='$name'");
            if ($check->num_rows == 0) {
                $sql = "INSERT INTO visa_services (country_id, visa_name, price, processing_time, validity, status) 
                        VALUES ($c_id, '$name', $price, '$time', '$validity', 'active')";
                $conn->query($sql);
                echo "Added $name for $country_name<br>";
            } else {
                echo "Skipped $name (already exists)<br>";
            }
        }
    }
}

echo "<br>Done! <a href='visa-service.php'>Check Frontend</a>";
?>
