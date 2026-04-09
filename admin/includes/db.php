<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gowilds_db';

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* 
// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($database);
} else {
    die("Error creating database: " . $conn->error);
}

// Create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS tours (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category VARCHAR(255) DEFAULT 'General',
        destination VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        duration VARCHAR(100),
        status ENUM('active', 'inactive') DEFAULT 'active',
        image VARCHAR(255) DEFAULT 'assets/images/tour-1.jpg',
        description TEXT,
        max_people INT DEFAULT 25,
        tour_type VARCHAR(255) DEFAULT 'City Tour',
        more_info TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS bookings (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50),
        tour_id INT(11),
        booking_date DATE,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS flight_searches (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        trip_type VARCHAR(50) DEFAULT 'One way',
        origin VARCHAR(255),
        destination VARCHAR(255),
        departure_date DATE,
        return_date DATE NULL,
        adults INT DEFAULT 1,
        children INT DEFAULT 0,
        infants INT DEFAULT 0,
        travel_class VARCHAR(50) DEFAULT 'Economy',
        special_fare VARCHAR(50) DEFAULT 'Regular',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS tour_bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tour_id INT(11) NOT NULL,
        customer_name VARCHAR(100) NOT NULL,
        customer_email VARCHAR(100) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        booking_date DATE NOT NULL,
        adults INT DEFAULT 1,
        children INT DEFAULT 0,
        infants INT DEFAULT 0,
        hotel_price DECIMAL(10,2) NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS itineraries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tour_id INT(11) NOT NULL,
        day_number INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS itinerary_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        itinerary_id INT(11) NOT NULL,
        image VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (itinerary_id) REFERENCES itineraries(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tour_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        rating INT CHECK (rating >= 1 AND rating <= 5),
        comment TEXT,
        status ENUM('pending', 'approved') DEFAULT 'approved',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tour_id INT NOT NULL,
        user_ip VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $table) {
    if (!$conn->query($table)) {
        die("Error creating table: " . $conn->error);
    }
}

// Check if new columns exist in tours table
$checkCols = [
    'category' => "VARCHAR(255) DEFAULT 'General'",
    'image' => "VARCHAR(255) DEFAULT 'assets/images/tour-1.jpg'",
    'description' => "TEXT",
    'max_people' => "INT DEFAULT 25",
    'tour_type' => "VARCHAR(255) DEFAULT 'City Tour'",
    'price_3star' => "DECIMAL(10,2) DEFAULT 0.00",
    'price_4star' => "DECIMAL(10,2) DEFAULT 0.00",
    'price_5star' => "DECIMAL(10,2) DEFAULT 0.00",
    'price_camps' => "DECIMAL(10,2) DEFAULT 0.00",
    'price_homestay' => "DECIMAL(10,2) DEFAULT 0.00",
    'more_info' => "TEXT"
];
foreach($checkCols as $col => $def) {
    $chk = $conn->query("SHOW COLUMNS FROM tours LIKE '$col'");
    if($chk && $chk->num_rows == 0) {
        $conn->query("ALTER TABLE tours ADD COLUMN $col $def");
    }
}
*/

// Just select the database
if (!$conn->select_db($database)) {
    die("Error selecting database: " . $conn->error);
}

// Check if admin user exists, if not create default admin
$result = $conn->query("SELECT id FROM users WHERE role='admin'");
if ($result && $result->num_rows == 0) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (name, email, password, role) VALUES ('Super Admin', 'admin@gowilds.com', '$password', 'admin')");
}
?>
