<?php
// reset_password.php

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kean_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the token is present in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Set timezone to Asia/Manila
    date_default_timezone_set('Asia/Manila');

    // Prepare and execute query to fetch the user associated with this token
    $sql = "SELECT * FROM password_resets WHERE token = ? AND token_expires > NOW()";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error in preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, show password reset form
        echo '
        <html>
        <head>
            <style>
                body {
                    background-color: #e2ded0; /* Soft grayish vintage color */
                    color: #4d4a47; /* Dark gray for text */
                    font-family: "Poppins", sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .container {
                    background-color: #f2efe9; /* Light vintage tone */
                    border-radius: 8px;
                    padding: 2rem;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                    max-width: 400px;
                    text-align: center;
                }
                .container h2 {
                    font-size: 1.6rem;
                    color: #4d4a47;
                    font-weight: 600;
                    margin-bottom: 1rem;
                }
                .container label {
                    display: block;
                    font-size: 1rem;
                    font-weight: 600;
                    color: #4d4a47;
                    margin-bottom: 0.5rem;
                }
                .container input {
                    width: 100%;
                    padding: 0.75rem;
                    font-size: 1rem;
                    border: 1px solid #b1aca3;
                    border-radius: 4px;
                    background-color: #fbf9f7;
                    margin-bottom: 1rem;
                    color: #4d4a47;
                }
                .container button {
                    width: 100%;
                    padding: 0.75rem;
                    font-size: 1rem;
                    color: #ffffff;
                    background-color: #80796b;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                .container button:hover {
                    background-color: #6f675a;
                }
                .container a {
                    display: inline-block;
                    padding: 10px 20px;
                    font-weight: bold;
                    text-decoration: none;
                    background-color: #80796b;
                    color: #ffffff;
                    border-radius: 4px;
                    margin-top: 1rem;
                    transition: background-color 0.3s ease;
                }
                .container a:hover {
                    background-color: #6f675a;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Reset Your Password</h2>
                <form action="process_reset.php" method="POST">
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" required>

                    <label for="confirmPassword">Confirm New Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>

                    <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                    
                    <button type="submit">Reset Password</button>
                </form>
            </div>
        </body>
        </html>';
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "Invalid or expired token.";
}

// Close connection
$stmt->close();
$conn->close();
?>
