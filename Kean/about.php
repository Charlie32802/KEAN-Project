<?php
session_start();

// Ensure the user is logged in by checking the session
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About KEAN</title>
    <link rel="icon" href="logo.png" type="image/png">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(0, 255, 255, 0.450);
            margin: 0;
            padding: 20px;
        }

        /* About Container */
        .about-container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            border: 3px solid black;
        }

        /* Header */
        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #333;
        }

        p {
            font-size: 1.1em;
            line-height: 1.6em;
            color: #333;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 50px;
        }

        .nav-link {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            color: #000000;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background-color: black;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .nav-link:hover::after {
            transform: translateX(0);
        }

        /* Button Styles */
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- About Container -->
    <div class="about-container">
        <h1>About KEAN</h1>

        <p><strong>KEAN</strong> (Keep Every Asset Neatly) is a finance tracking website designed to help users easily manage their personal finances. It provides a user-friendly interface that allows users to set their balance and salary, create and track their expenses, and view their history in what activities did they interact within the dashboard.</p>

        <p>The website features an intuitive dashboard where you can:</p>
        <ul>
            <li>Set and update your balance and salary</li>
            <li>Add and view your expenses to show the description, amount, the date and time it was created and the actions in which you can edit, delete or pay</li>
            <li>View your history of expenses at any time</li>
        </ul>

        <p>KEAN was created to simplify financial management and help you make informed decisions about your money. With our responsive design, you can access your financial data anytime, anywhere, and keep track of your finances effortlessly!</p>

        <h2>Features:</h2>
        <ul>
            <li>User-friendly dashboard</li>
            <li>Real-time balance and salary updates</li>
            <li>Easy expense tracking and history view</li>
            <li>Responsive and mobile-friendly</li>
        </ul>

        <button class="button" onclick="window.location.href='dashboard.php';">Go Back</button>
    </div>

</body>
</html>
