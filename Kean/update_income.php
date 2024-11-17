<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kean_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$user_id = $_SESSION['user_id'];
$balance = $_POST['balance'];
$salary = $_POST['salary'];

// Ensure balance and salary are valid
if (!is_numeric($balance) || !is_numeric($salary)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
    exit;
}

// Update balance and salary in the database
$sql = "UPDATE users SET balance = ?, salary = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dii", $balance, $salary, $user_id);
$result = $stmt->execute();

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update income']);
}

$stmt->close();
$conn->close();
?>