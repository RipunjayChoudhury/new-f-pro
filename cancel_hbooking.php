<?php
session_start();
include 'dbc.php'; // Include database connection file

if (!isset($_SESSION['name'])) {
    echo "<script>alert('Please log in to cancel bookings.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Get user details
$userEmail = $_SESSION['name'];

// Get the booking ID from the request
$booking_id = $_GET['id'] ?? null;

if (!$booking_id) {
    echo "<script>alert('Invalid booking ID.');</script>";
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}

// Verify that the booking belongs to the logged-in user
$checkQuery = $con->prepare("SELECT * FROM bhotal WHERE id = ? AND user_email = ?");
$checkQuery->bind_param("is", $booking_id, $userEmail);
$checkQuery->execute();
$booking = $checkQuery->get_result()->fetch_assoc();

if (!$booking) {
    echo "<script>alert('Booking not found or unauthorized access.');</script>";
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}

// Delete the booking
$deleteQuery = $con->prepare("DELETE FROM bhotal WHERE id = ?");
$deleteQuery->bind_param("i", $booking_id);

if ($deleteQuery->execute()) {
    echo "<script>alert('Booking canceled successfully.');</script>";
} else {
    echo "<script>alert('Error canceling booking: " . $con->error . "');</script>";
}

echo "<script>window.location.href='profile.php';</script>";
?>
