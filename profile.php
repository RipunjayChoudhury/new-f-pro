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


// Fetch bookings for the logged-in user
$user_email = $_SESSION['name'];
$movie_bookings_query = "SELECT * FROM mbookings WHERE user_email = '$user_email'";
$hotel_bookings_query = "SELECT * FROM bhotal WHERE user_email = '$user_email'";

$movie_bookings = mysqli_query($con, $movie_bookings_query);
$hotel_bookings = mysqli_query($con, $hotel_bookings_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - BookEasy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #ffbe58;
            text-align: center;
        }
        h3 {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .action-btn {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .edit-btn {
            background-color: #007bff;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .cancel-btn {
            background-color: #dc3545;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <h3>Welcome, <?= htmlspecialchars($userData['fname']); ?></h3>

        <!-- Movie Bookings -->
        <h3>Your Movie Bookings:</h3>
        <?php if (mysqli_num_rows($movie_bookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Movie Name</th>
                        <th>Date</th>
                        <th>Seats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($movie_bookings)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['mname']); ?></td>
                            <td><?= htmlspecialchars($row['b_date']); ?></td>
                            <td><?= htmlspecialchars($row['seat_no']); ?></td>
                            <td>
                                
                                <a href="cancel_mbooking2.php?id=<?= $row['id']; ?>" class="action-btn cancel-btn">Cancel</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no movie bookings.</p>
        <?php endif; ?>

        <!-- Hotel Bookings -->
        <h3>Your Hotel Bookings:</h3>
        <?php if (mysqli_num_rows($hotel_bookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Hotel Name</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($hotel_bookings)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['hotel']); ?></td>
                            <td><?= htmlspecialchars($row['cin']); ?></td>
                            <td><?= htmlspecialchars($row['cout']); ?></td>
                            <td>
                                
                                <a href="cancel_hbooking.php?id=<?= $row['id']; ?>" class="action-btn cancel-btn">Cancel</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no hotel bookings.</p>
        <?php endif; ?>

        <a href="home2.php" class="action-btn edit-btn">Back to Dashboard</a>
    </div>
</body>
</html>
