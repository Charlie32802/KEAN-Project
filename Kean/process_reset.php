<?php
// process_reset.php

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

// Check if the token and new password are set
if (isset($_POST['token']) && isset($_POST['newPassword']) && isset($_POST['confirmPassword'])) {
    $token = $_POST['token'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if the new password matches the confirmation password
    if ($newPassword === $confirmPassword) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare SQL query to update the user's password
        $sql = "UPDATE users SET password = ? WHERE email = (SELECT email FROM password_resets WHERE token = ? AND token_expires > NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $token);
        $stmt->execute();

        // Check if the password was updated
        if ($stmt->affected_rows > 0) {
            // Delete the token from the password_resets table
            $deleteSql = "DELETE FROM password_resets WHERE token = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("s", $token);
            $deleteStmt->execute();

            echo '
            <html>
            <head>
                <style>
                    body {
                        background-color: #e2ded0;
                        color: #4d4a47;
                        font-family: "Poppins", sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        margin: 0;
                    }
                    .message-container {
                        background-color: #f2efe9;
                        border-radius: 8px;
                        padding: 2rem;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                        max-width: 400px;
                        text-align: center;
                    }
                    .message-container h1 {
                        font-size: 24px;
                        color: #4d4a47;
                        font-weight: 600;
                        margin-bottom: 1.5rem;
                    }
                    .message-container p {
                        color: #6e6a65;
                        line-height: 1.5;
                        margin-bottom: 1.5rem;
                    }
                    .message-container a {
                        display: inline-block;
                        padding: 10px 20px;
                        font-weight: bold;
                        text-decoration: none;
                        background-color: #80796b;
                        color: #ffffff;
                        border-radius: 4px;
                        transition: background-color 0.3s ease;
                    }
                    .message-container a:hover {
                        background-color: #6f675a;
                    }
                </style>
            </head>
            <body>
                <div class="message-container">
                    <h1>Password Reset Successful</h1>
                    <p>Your password has been successfully reset.</p>
                    <a href="index.html">Return to Home</a>
                </div>

                <!-- Add the audio element for looping sound -->
            <audio autoplay loop>
                <source src="yeahhh.mp3" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>

            </body>
            </html>';
        } else {
            echo "There was an issue resetting your password.";
        }
    } else {
        echo "Passwords do not match.";
    }
} else {
    echo "Invalid request.";
}

// Close connection
$stmt->close();
$conn->close();
?>
