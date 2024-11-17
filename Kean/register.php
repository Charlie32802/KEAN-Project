<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kean_database";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$confirm_pass = $_POST['confirmPassword'];
$profile_picture = $_FILES['profilePicture'];

// Check if passwords match
if ($pass !== $confirm_pass) {
    echo "Passwords do not match!";
    exit;
}

// Hash the password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Default profile picture if none is uploaded
$profile_pic_path = "default-profile-pic.jpg";

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
    $profile_picture = $_FILES['profilePicture'];
    $upload_dir = "uploads/";
    $profile_pic_path = $upload_dir . basename($profile_picture['name']);
    
    if (!move_uploaded_file($profile_picture['tmp_name'], $profile_pic_path)) {
        echo "Failed to upload profile picture.";
        exit;
    }
}

$sql = "INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $user, $email, $hashed_password, $profile_pic_path);

if ($stmt->execute()) {
    header("Location: success.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>
