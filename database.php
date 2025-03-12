<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "EventManagementDB";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $conn->exec("CREATE DATABASE IF NOT EXISTS $database");
    $conn->exec("USE $database");

    // Create Users Table (Combines organizers, attendees, and potentially admins)
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        contact_number VARCHAR(20),
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('organizer', 'attendee', 'admin') NOT NULL DEFAULT 'attendee'
    )");

    // Create Events Table
    $conn->exec("CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        venue_name VARCHAR(255) NOT NULL,
        venue_location VARCHAR(255) NOT NULL,
        event_name VARCHAR(255) NOT NULL,
        event_day_time DATETIME NOT NULL,
        event_amount DECIMAL(10, 2),
        is_free_event TINYINT(1) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create Booking Table
    $conn->exec("CREATE TABLE IF NOT EXISTS booking (
        booking_id INT AUTO_INCREMENT PRIMARY KEY,
        event_id INT NOT NULL,
        user_id INT NOT NULL,
        booking_date DATETIME NOT NULL,
        UNIQUE(event_id, user_id),
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>