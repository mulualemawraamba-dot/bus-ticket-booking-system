<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_ticket_system";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db($dbname);

/* =========================
   CREATE TABLES
========================= */

// USERS TABLE
$conn->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','officer','driver','passenger') NOT NULL,
    status ENUM('approved','pending') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

// BOOKINGS TABLE
$conn->query("
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    from_city VARCHAR(50) NOT NULL,
    to_city VARCHAR(50) NOT NULL,
    journey_date DATE NOT NULL,
    seats VARCHAR(100) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('active','canceled') DEFAULT 'active',
    payment_status ENUM('pending','paid','refunded') DEFAULT 'pending',
    verified TINYINT(1) DEFAULT 0,
    verified_by VARCHAR(100) DEFAULT NULL,
    verified_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

// BOOKED SEATS TABLE
$conn->query("
CREATE TABLE IF NOT EXISTS booked_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    journey_date DATE NOT NULL,
    from_city VARCHAR(50) NOT NULL,
    to_city VARCHAR(50) NOT NULL,
    seat_number INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seat (journey_date, from_city, to_city, seat_number)
) ENGINE=InnoDB;
");

// DRIVER ROUTES TABLE
$conn->query("
CREATE TABLE IF NOT EXISTS driver_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_email VARCHAR(150) NOT NULL,
    route_from VARCHAR(50) NOT NULL,
    route_to VARCHAR(50) NOT NULL,
    travel_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL,
    vehicle_serial VARCHAR(50) NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_driver_date (driver_email, travel_date)
) ENGINE=InnoDB;
");

// ROUTES TABLE
$conn->query("
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_city VARCHAR(50) NOT NULL,
    to_city VARCHAR(50) NOT NULL,
    distance_km INT NOT NULL,
    UNIQUE KEY unique_route (from_city, to_city)
) ENGINE=InnoDB;
");

// PAYMENTS TABLE
$conn->query("
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    passenger_id INT NOT NULL,
    passenger_email VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    bank_name VARCHAR(100),
    transaction_ref VARCHAR(100),
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

$conn->query("
CREATE TABLE IF NOT EXISTS driver_trip_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_email VARCHAR(150) NOT NULL,
    travel_date DATE NOT NULL,
    trip_status ENUM('not_started','on_the_way','completed') DEFAULT 'not_started',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

echo "All tables created successfully!";

$conn->close();
?>