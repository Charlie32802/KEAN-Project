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
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch balance and salary from the database
$sql = "SELECT balance, salary FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance, $salary);
$stmt->fetch();

if ($balance !== null && $salary !== null) {
    echo json_encode(['success' => true, 'balance' => $balance, 'salary' => $salary]);
} else {
    echo json_encode(['success' => false, 'message' => 'Data not found']);
}

$stmt->close();
$conn->close();
?>