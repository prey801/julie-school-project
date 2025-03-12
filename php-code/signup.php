<?php
include "mydb_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Validate username
    if (strpos($username, "/") === false && strpos($username, ".") === false) {
        echo "<script>alert('Username must contain a / or .'); window.location.href = 'signup.php';</script>";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Determine table based on role
    $table = "";
    if ($role == "organizer") $table = "organizers";
    elseif ($role == "attendee") $table = "attendees";
    elseif ($role == "admin") $table = "admins";
    else {
        echo "<script>alert('Invalid role selected!');</script>";
        exit();
    }

    // Insert into the appropriate table
    $stmt = $conn->prepare("INSERT INTO $table (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error during registration. Try again.');</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>
