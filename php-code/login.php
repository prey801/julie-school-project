<?php
session_start();
include "mydb_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Determine table based on role
    $table = "";
    if ($role == "organizer") $table = "organizers";
    elseif ($role == "attendee") $table = "attendees";
    elseif ($role == "admin") $table = "admins";
    else {
        echo "<script>alert('Invalid role selected!');</script>";
        exit();
    }

    // Fetch user data
    $stmt = $conn->prepare("SELECT id, password FROM $table WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();
        
        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["role"] = $role;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href = 'login.php';</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>
