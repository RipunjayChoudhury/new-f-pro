<?php
session_start();
if (!isset($_SESSION['name'])) {
    echo "<script>alert('Please log in to access this page.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
include 'dbc.php'; // Include the database connection file

if (!isset($_SESSION['name'])) {
    echo "<script>alert('Please log in to book a movie.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Get user details from the users table
$userEmail = $_SESSION['name'];
$userQuery = $con->prepare("SELECT * FROM users WHERE email = ?");
$userQuery->bind_param("s", $userEmail);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();
if (!$userData) {
    echo "<script>alert('User not found. Please log in again.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookEasy - Dashboard</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            
            text-align: center;
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #ffbe58;
            font-size: 28px;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .btn {
            display: block;
            width: 90%;
            margin: 10px auto;
            padding: 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #28a745;
        }

        .btn-secondary:hover {
            background-color: #1e7e34;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>BookEasy</h1>
        <h3>Welcome, <?= htmlspecialchars($userData['fname']); ?></h3>
        <p>What would you like to do today?</p>

        <!-- Booking Options -->
        <a href="movie2.php" class="btn">Book a Movie</a>
        <a href="hotelbooking2.php" class="btn btn-secondary">Book a Hotel</a>

        <!-- Profile and Logout -->
        <a href="profile.php" class="btn">View Profile</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
