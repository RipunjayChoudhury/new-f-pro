<?php
session_start();
session_unset();
session_destroy();
echo "<script>alert('You have been logged out successfully.');</script>";
echo "<script>window.location.href='home.php';</script>";
exit();
?>
