<?php
session_start(); // Start the session to track user login status

// Function to check if the user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }
        .header {
            background-color: #ffbe58;
            color: white;
            padding: 20px 0;
        }
        .header h1 {
            margin: 0;
        }
        .button {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .button-secondary {
            background-color: #28a745;
        }
        .button-secondary:hover {
            background-color: #1e7e34;
        }
        /* Popup Styles */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }
        .popup h3 {
            margin-bottom: 20px;
        }
        .popup button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup button:hover {
            background-color: #0056b3;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body><center>
    <div class="header">
        <h1>Welcome to Book Easy</h1>An integrated booking system for Movies and Hotels
    </div>
    <div class="container">
        <h2>Welcome! Please explore your options below.</h2>
        <!-- Booking Options -->
        <button 
            class="button" 
            onclick="<?= isLoggedIn() ? "window.location.href='movie_booking.php'" : "showPopup()" ?>">
            Book a Movie
        </button>
        <button 
            class="button button-secondary" 
            onclick="<?= isLoggedIn() ? "window.location.href='hotel_booking.php'" : "showPopup()" ?>">
            Book a Hotel
        </button>
        <br><br>
        <!-- Login/Signup or User-Specific Options -->
        <?php if (isLoggedIn()): ?>
            <h2>Hello, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
            <a href="profile.php" class="button">View Profile</a>
            <a href="logout.php" class="button button-secondary">Logout</a>
        <?php else: ?>
            <h3>Please log in or sign up to book movies or hotels.</h3>
            <a href="login.php" class="button">Login</a>
            <a href="signup.php" class="button button-secondary">Sign Up</a>
        <?php endif; ?>
    </div>

    <!-- Popup and Overlay -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <h3>You need to log in to book a service!</h3>
        
        <button onclick="window.location.href='login.php'">Login</button>
        <p><h3>Don't have an accout,</h3></p>
        <p> Please sign up to continue</p>
        <button onclick="window.location.href='signup.php'">Sign Up</button>
        <p><button onclick="hidePopup()">Cancel</button></p>
    </div>

    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</center></body>
</html>
