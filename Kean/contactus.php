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
    <title>Contact Us</title>
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="stylesheet" href="dashboardstyles.css">
    <style>
        /* Additional styling for the Contact Us page */
        .contact-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-wrap: wrap;
            gap: 30px;
        }

        .profile-card {
            width: 300px;
            background-color: white;
            border-radius: 15px;
            border: 2px solid #4CAF50;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .profile-card:hover {
            transform: scale(1.05);
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 2px solid #4CAF50;
            object-fit: cover;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 15px;
        }

        .profile-card h2 {
            color: #333;
            font-size: 1.6em;
            margin: 10px 0;
        }

        .profile-card p {
            color: #4CAF50;
            font-size: 1em;
            margin: 8px 0;
        }

        /* Changed the details text color to black */
        .profile-card .details p {
            color: black; /* Change the text color of the details to black */
            font-size: 1em;
            margin: 8px 0;
        }

        .profile-card .details span {
            font-weight: bold;
        }

        /* Styling for the Go Back button */
        .go-back-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .go-back-btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Small screen responsive design */
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }
        }

        /* Align the button at the bottom */
        .go-back-container {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-top: 10px;  /* Reduced margin-top to bring the button closer to the top */
}
        
    </style>
</head>
<body>
    <div class="contact-container">
        <!-- Isaiah Rafael Peña Card -->
        <div class="profile-card">
            <img src="isaiah-profile-pic.jpeg" alt="Isaiah Rafael Peña" class="profile-picture">
            <h2>Isaiah Rafael Peña</h2>
            <div class="details">
                <p><span>Name:</span> Isaiah Rafael Peña</p>
                <p><span>Date of Birth:</span> February 2, 2003</p>
                <p><span>Date of Death:</span> Soon</p>
                <p><span>Age:</span> 21</p>
                <p><span>Address:</span> Sison</p>
                <p><span>Course:</span> Bachelor of Science in Information Technology</p>
                <p><span>Email:</span> nigahi_na2k24@yahoo.com</p>
                <p><span>Contact Number:</span> 09123456789</p>
            </div>
        </div>

        <!-- Marc Daryll Trinidad Card -->
        <div class="profile-card">
            <img src="marc-profile-pic.jpg" alt="Marc Daryll Trinidad" class="profile-picture">
            <h2>Marc Daryll Trinidad</h2>
            <div class="details">
                <p><span>Name:</span> Marc Daryll Trinidad</p>
                <p><span>Date of Birth:</span> March 28, 2002</p>
                <p><span>Date of Death:</span> Soon</p>
                <p><span>Age:</span> 22</p>
                <p><span>Address:</span> Canlanipa Housing</p>
                <p><span>Course:</span> Bachelor of Science in Information Technology</p>
                <p><span>Email:</span> marcdaryll.trinidad@gmail.com</p>
                <p><span>Contact Number:</span> 09999502360</p>
            </div>
        </div>
    </div>

    <!-- Go Back Button Container -->
    <div class="go-back-container">
        <a href="dashboard.php" class="go-back-btn">Go Back</a>
    </div>
</body>
</html>
