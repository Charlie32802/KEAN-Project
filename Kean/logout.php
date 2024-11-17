<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the timezone to your local timezone (replace 'Asia/Manila' with your timezone if needed)
date_default_timezone_set('Asia/Manila');  // Set the timezone to Asia/Manila

// Database configuration
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "kean_database";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Get the user ID and username from the session
    $user_id = $_SESSION['user_id'];
    
    // Retrieve the username from the database
    $sql = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Log user activity - "Logged out"
    $activity = $username . " logged out";  // Include the username in the activity message
    $timestamp = date("Y-m-d H:i:s");  // Get current timestamp

    // Insert the activity into the user_activity table
    $activity_sql = "INSERT INTO user_activity (user_id, activity, timestamp) VALUES (?, ?, ?)";
    $activity_stmt = $conn->prepare($activity_sql);
    $activity_stmt->bind_param("iss", $user_id, $activity, $timestamp);
    $activity_stmt->execute();
    $activity_stmt->close();

    // Destroy the session to log out
    session_destroy();
}

// Redirect to the login page
header("Location: index.html");
exit;
?>
