<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila');

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

// Retrieve login credentials from POST request
$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare SQL query to check if the user exists in the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify the password using password_verify
    if (password_verify($pass, $row['password'])) {
        // Password matches
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        echo "success";

        $user_id = $_SESSION['user_id'];
        $username = $row['username'];
        $activity = $username . " logged in";
        $timestamp = date("Y-m-d H:i:s");

        $activity_sql = "INSERT INTO user_activity (user_id, activity, timestamp) VALUES (?, ?, ?)";
        $activity_stmt = $conn->prepare($activity_sql);
        $activity_stmt->bind_param("iss", $user_id, $activity, $timestamp);
        $activity_stmt->execute();
        $activity_stmt->close();

    } else {
        echo "error"; // Invalid password
    }
} else {
    echo "error"; // Invalid username
}

$stmt->close();
$conn->close();

?>
