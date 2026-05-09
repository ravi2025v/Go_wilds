<?php
include 'admin/includes/db.php';

$tables = [
    "CREATE TABLE IF NOT EXISTS visa_countries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        country_name VARCHAR(255) NOT NULL,
        country_image VARCHAR(255) DEFAULT 'assets/images/country-default.jpg',
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS visa_services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        country_id INT NOT NULL,
        visa_name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        processing_time VARCHAR(100),
        validity VARCHAR(100),
        requirements TEXT,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (country_id) REFERENCES visa_countries(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS visa_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        country_id INT NOT NULL,
        service_id INT NOT NULL,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        passport_no VARCHAR(50) NOT NULL,
        dob DATE NOT NULL,
        gender VARCHAR(20),
        place_issue VARCHAR(255),
        issue_date DATE,
        expiry_date DATE,
        status ENUM('pending', 'review', 'approved', 'rejected') DEFAULT 'pending',
        documents JSON,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (country_id) REFERENCES visa_countries(id),
        FOREIGN KEY (service_id) REFERENCES visa_services(id)
    )"
];

echo "<h2>Creating Visa Tables...</h2>";

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Add some default countries if none exist
$check = $conn->query("SELECT id FROM visa_countries LIMIT 1");
if ($check->num_rows == 0) {
    $default_countries = ['France', 'Germany', 'Japan', 'UAE', 'USA', 'UK', 'Australia', 'India'];
    foreach ($default_countries as $country) {
        $conn->query("INSERT INTO visa_countries (country_name) VALUES ('$country')");
    }
    echo "Default countries added.<br>";
}

echo "<br><a href='admin/index.php'>Go to Admin Dashboard</a>";
?>
