<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "kean_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user activity data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user_activity WHERE user_id = ? ORDER BY timestamp DESC LIMIT 10";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense History</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="historystyles.css">
</head>
<body>
    <div class="history-container">
        <header>
            <h1>Expense History</h1>
            <a href="dashboard.php" class="back-link">Back to Dashboard</a>
        </header>

        <section class="history-details">
            <h2>User Activity</h2>
            <ul class="history-list">
                <?php
                while ($row = $result->fetch_assoc()) {
                    $formatted_time = date('F j, Y \a\t g:i A', strtotime($row['timestamp']));
                    echo "<li>{$row['activity']} on {$formatted_time}</li>";
                }
                ?>
            </ul>
        </section>

        <footer>
            <p>&copy; 2024 Keep Every Asset Noticed</p>
        </footer>
    </div>

    <script src="historyjava.js"></script>
</body>
</html>

<?php
$conn->close();
?>
