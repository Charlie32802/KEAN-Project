<?php
// Include PHPMailer classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database credentials
$servername = "localhost"; // Change this if you're using a different server
$username = "root";        // Your database username (default is "root")
$password = "";            // Your database password (leave empty if no password)
$dbname = "kean_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the email was submitted through POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the email from the form input
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    if ($email) {
        // Check if the email is from Gmail or Yahoo
        $allowed_domains = ['gmail.com', 'yahoo.com'];
        $email_domain = substr(strrchr($email, "@"), 1); // Extract the domain

        if (in_array($email_domain, $allowed_domains)) {
            // Set timezone to Asia/Manila
            date_default_timezone_set('Asia/Manila');
            
            // Generate a unique token for password reset
            $token = bin2hex(random_bytes(32)); // Create a random 64-character token
            $expiration_time = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expiration time (1 hour from now)

            // Insert the token and expiration time into the database
            $sql = "INSERT INTO password_resets (email, token, token_expires) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $email, $token, $expiration_time);
            $stmt->execute();

            // Create a password reset link
            $reset_link = "http://localhost/kean/reset_password.php?token=" . $token;

            // Fetch the username from the database
            $sql_user = "SELECT username FROM users WHERE email = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("s", $email);
            $stmt_user->execute();
            $stmt_user->bind_result($username);
            $stmt_user->fetch();
            $stmt_user->close();

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // Set up SMTP server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'marcdaryll.trinidad@gmail.com';  // Your Gmail address
                $mail->Password = 'xipkfvgxmqewsemg';  // App Password (not your Gmail password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use STARTTLS
                $mail->Port = 587;  // SMTP port for Gmail

                // Sender and recipient details
                $mail->setFrom('no-reply@kean.com', 'KEAN Support');
                $mail->addAddress($email);  // Add the recipient email

                // Set the subject with the username
                $mail->Subject = 'Password Reset for ' . $username;

                // Set the body of the email with HTML and inline CSS
                $mail->isHTML(true);  // Enable HTML format
                $mail->Body = "
                    <html>
                    <head>
                        <style>
                            body {
                font-family: 'Poppins', sans-serif;
                color: #4d4a47;
                background-color: #e2ded0;
                margin: 0;
                padding: 0;
            }
            .email-container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                background-color: #f2efe9;
                border-radius: 8px;
                padding: 30px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            .email-header {
                text-align: center;
                color: #4d4a47;
            }
            .email-header h1 {
                font-size: 26px;
                margin: 0;
            }
            .email-body {
                font-size: 16px;
                color: #4d4a47;
                line-height: 1.6;
                margin-bottom: 20px;
            }
            .email-button {
                display: inline-block;
                padding: 12px 25px;
                background-color: #80796b; /* Vintage gray button */
                color: #000000 !important; /* Set text color to black */
                text-decoration: none;
                font-weight: bold;
                border-radius: 4px;
                transition: background-color 0.3s;
            }
            .email-button:hover {
                background-color: #6f675a; /* Darker vintage gray for hover */
            }
            .email-footer {
                text-align: center;
                font-size: 12px;
                color: #6e6a65;
            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='email-header'>
                                <h1>Password Reset Request</h1>
                            </div>
                            <div class='email-body'>
                                <p>Hello $username,</p>
                                <p>We received a request to reset the password for your KEAN account associated with this email address.</p>
                                <p>To reset your password, please click the button below:</p>
                                <p style='text-align: center;'>
                                    <a href='$reset_link' class='email-button'>Reset Your Password</a>
                                </p>
                                <p>If you didn’t request a password reset, you can safely ignore this email. Your password will remain unchanged.</p>
                            </div>
                            <div class='email-footer'>
                                <p>Thank you for using KEAN. If you have any questions, feel free to contact us by clicking our names below.</p>
                            <p>
                                <a href='https://www.facebook.com/isaiahrafael09' style='color: #4d4a47; text-decoration: none;'>Isaiah Rafael Peña</a> |
                                <a href='https://www.facebook.com/marcdaryll.trinidad' style='color: #4d4a47; text-decoration: none;'>Marc Daryll Trinidad</a>
                            </p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";

                // Send the email
                $mail->send();

                // Display the success message with styling
                echo '
                <html>
                <head>
                    <style>
                        body {
                            background-color: #e2ded0; /* Soft grayish vintage background */
            color: #4d4a47; /* Dark gray text */
            font-family: "Poppins", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .message-container {
            background-color: #f2efe9; /* Light grayish background for contrast */
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            text-align: center;
        }
        .message-container h1 {
            font-size: 24px;
            color: #4d4a47; /* Dark gray text */
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .message-container p {
            color: #6e6a65; /* Mid-tone gray text */
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .message-container a {
            display: inline-block;
            padding: 10px 20px;
            font-weight: bold;
            text-decoration: none;
            background-color: #80796b; /* Muted brownish-gray button */
            color: #ffffff;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .message-container a:hover {
            background-color: #6f675a; /* Darker shade on hover */
        }
                    </style>
                </head>
                <body>
                    <div class="message-container">
                        <h1>Password Reset Successful</h1>
                        <p>A password reset link has been sent to your email address.</p>
                        <p>Please check your inbox (and spam folder) for further instructions.</p>
                    </div>
                </body>
                </html>';
            } catch (Exception $e) {
                echo "There was an issue sending the recovery email: " . $mail->ErrorInfo;
            }
        } else {
            echo "Only Gmail or Yahoo email addresses are supported for recovery.";
        }
    } else {
        echo "Invalid email address. Please enter a valid email.";
    }
} else {
    // Redirect to the form if accessed directly
    header("Location: forgot_password.html");
    exit;
}
?>
