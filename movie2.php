<?php
// Start session
session_start();
include 'dbc.php'; // Include your database connection file

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

// Initialize variables
$locations = [];
$halls = [];
$movies = [];
$selected_location = '';
$selected_hall = '';
$selected_movie = '';

// Fetch locations
$locations_result = $con->query("SELECT * FROM mlocation");
while ($row = $locations_result->fetch_assoc()) {
    $locations[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['location_id'])) {
        $selected_location = $_POST['location_id'];
        // Fetch halls for the selected location
        $halls_result = $con->query("SELECT * FROM movie_hall WHERE location_id = $selected_location");
        while ($row = $halls_result->fetch_assoc()) {
            $halls[] = $row;
        }
    }

    if (isset($_POST['hall_id'])) {
        $selected_hall = $_POST['hall_id'];
        // Fetch movies for the selected hall
        $movies_result = $con->query("SELECT * FROM movie WHERE hall_id = $selected_hall");
        while ($row = $movies_result->fetch_assoc()) {
            $movies[] = $row;
        }
    }

    if (isset($_POST['movie_id'])) {
        $selected_movie = $_POST['movie_id'];
        $seats_booked = $_POST['seats_booked'];
    
        // Prepare other variables for the query
        $user_email = $userData['email']; // Assuming 'email' exists in your 'users' table
        $booking_date = date('Y-m-d'); // Current date for booking
        $seat_number = $seats_booked; // Assuming seat number is the same as seats booked
    
        // Insert booking
        $stmt = $con->prepare("INSERT INTO mbookings ( user_email, mname, hall_id, location_id, b_date, seat_no) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisi",  $user_email, $selected_movie_name, $selected_hall, $selected_location, $booking_date, $seat_number);
    
        // Fetch movie name for 'mname'
        $movieQuery = $con->prepare("SELECT name FROM movie WHERE id = ?");
        $movieQuery->bind_param("i", $selected_movie);
        $movieQuery->execute();
        $movieResult = $movieQuery->get_result();
        $movieData = $movieResult->fetch_assoc();
        $selected_movie_name = $movieData['name']; // Movie name from the 'movie' table
    
        if ($stmt->execute()) {
            echo "<script>alert('Booking successful!');</script>";
            echo "<script>window.location.href='home2.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        select, input, button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            max-width: 300px;
        }
        .form-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($userData['fname']); ?></h1>
    <h2>Movie Booking System</h2>

    <!-- Location Selection -->
    <form method="POST">
        <div class="form-section">
            <label for="location">Select Location:</label>
            <select name="location_id" id="location" onchange="this.form.submit()">
                <option value="">--Select Location--</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= $location['id'] ?>" <?= $selected_location == $location['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($location['location']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <!-- Hall Selection -->
    <?php if (!empty($halls)): ?>
        <form method="POST">
            <div class="form-section">
                <label for="hall">Select Hall:</label>
                <input type="hidden" name="location_id" value="<?= $selected_location ?>">
                <select name="hall_id" id="hall" onchange="this.form.submit()">
                    <option value="">--Select Hall--</option>
                    <?php foreach ($halls as $hall): ?>
                        <option value="<?= $hall['id'] ?>" <?= $selected_hall == $hall['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($hall['hall_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

    <?php endif; ?>

    <!-- Movie Selection -->
    <?php if (!empty($movies)): ?>
        <form method="POST">
            <div class="form-section">
                <label for="movie">Select Movie:</label>
                <input type="hidden" name="location_id" value="<?= $selected_location ?>">
                <input type="hidden" name="hall_id" value="<?= $selected_hall ?>">
                <select name="movie_id" id="movie">
                    <option value="">--Select Movie--</option>
                    <?php foreach ($movies as $movie): ?>
                        <option value="<?= $movie['id'] ?>"><?= htmlspecialchars($movie['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="seats_booked">Seats to Book:</label>
            <input type="number" name="seats_booked" min="1" required>
            <button type="submit">Book Now</button>
        </form>

    <?php endif; ?>
</body>
</html>
