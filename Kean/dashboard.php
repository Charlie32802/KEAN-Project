<?php
session_start();

// Ensure the user is logged in by checking the session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Redirect to the login page if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "kean_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch username and profile picture from the database
$sql_user_details = "SELECT username, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_user_details);
$stmt->bind_param("i", $_SESSION['user_id']); // Use session user_id
$stmt->execute();
$stmt->bind_result($username, $profile_picture);
$stmt->fetch();
$stmt->close();

// Fetch balance and salary from the database
$sql_balance_salary = "SELECT balance, salary FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_balance_salary);
$stmt->bind_param("i", $_SESSION['user_id']); // Use session user_id
$stmt->execute();
$stmt->bind_result($balance, $salary);
$stmt->fetch();
$stmt->close();

// Set default profile picture if not set
if (empty($profile_picture)) {
    $profile_picture = "default-profile-pic.jpg";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KEAN Dashboard</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="dashboardstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="overlay" id="overlay"></div>

    <div class="dashboard-container">
        <h1>Dashboard</h1>
        <div id="clock" class="clock"></div>

        <div class="theme-toggle">
            <input type="checkbox" id="theme-switch">
            <label class="toggle-label" for="theme-switch">
                <div class="toggle-ball"></div>
            </label>
        </div>     

        <!-- Profile Section -->
        <div class="profile">
            <!-- Dynamically set profile picture and username -->
            <img class="profile-picture" src="<?php echo $profile_picture; ?>" alt="Profile Picture">
            <div class="user-name"><?php echo htmlspecialchars($username); ?></div>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="about.php" class="nav-link about">About</a>
            <a href="contactus.php" class="nav-link contact">Contact Us</a>
            <a href="logout.php" class="nav-link logout">Logout</a>
        </div>

        <!-- Income Section -->
        <div id="income-section" class="flex-row">
            <div id="input-section">
                <label for="input-balance">Balance:</label>
                <input type="number" id="input-balance" value="<?php echo $balance; ?>" placeholder="Enter your balance">
                <span class="error-message" id="balance-error">Please enter a valid balance.</span>
                <label for="input-salary">Salary:</label>
                <input type="number" id="input-salary" value="<?php echo $salary; ?>" placeholder="Enter your salary">
                <span class="error-message" id="salary-error">Please enter a valid salary.</span>
                <button id="set-income-btn" class="button">Set</button>
            </div>

            <div id="balance-section" style="display: none;">
                <p>Balance: <span id="balance"><?php echo '₱' . number_format($balance, 2); ?></span></p>
                <p>Salary: <span id="salary"><?php echo '₱' . number_format($salary, 2); ?></span></p>
                <button id="edit-income-btn" class="button">Edit</button>
            </div>
        </div>

        <!-- Add Expense Section -->
        <div>
            <h2>Create your Expense</h2>
            <div class="expense-inputs">
                <label for="description">Description:</label>
                <input type="text" id="description" placeholder="Expense description">
                <span class="error-message" id="description-error">Please enter a valid description.</span>
                <label for="expense-amount">Amount:</label>
                <input type="number" id="expense-amount" placeholder="Expense amount">
                <span class="error-message" id="amount-error">Please enter a valid amount.</span>
                <button id="add-expense-btn" class="button">Add</button>
            </div>

            <!-- Expenses Table -->
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="expense-list">
                    <!-- Dynamic content will be added here -->
                </tbody>
            </table>

            <!-- Total Expense Section -->
            <div id="total-expense-section" style="display: none;">
                <h3>Total Expense: <span id="total-expense">₱0.00</span></h3>
            </div>

            <div class="view-history">
                <a href="history.php" class="link">View History</a>
            </div>
        </div>

        <!-- Notification Modal -->
        <div id="notification" class="notification" style="display: none;">
            <div class="notification-content">
                <p id="notification-message"></p>
                <button id="yes-btn" class="button">Yes</button>
                <button id="no-btn" class="button">No</button>
            </div>
        </div>
    </div>

    <!-- Notification Banners -->
    <div id="paid-notification" class="notification-banner" style="display: none;">Paid Notification</div>
    <div id="delete-notification" class="notification-banner" style="display: none;">Delete Notification</div>

    <script src="dashboardjava.js"></script>
</body>
</html>