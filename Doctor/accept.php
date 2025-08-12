<?php
session_start();
include("../db.php");


if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'doctor') {
    exit("Access denied");
}


$doctorUsername = trim($_SESSION['username']);

$appointmentId = $_GET['id'] ?? null;

if (!$appointmentId) {
    exit("Invalid appointment ID.");
}

$check = mysqli_query($conn, "SELECT * FROM appointments 
                              WHERE appointment_id = '$appointmentId' 
                              AND doctor_username = '$doctorUsername'");

if (mysqli_num_rows($check) === 0) {
    exit("Unauthorized action.");
}

$update = mysqli_query($conn, "UPDATE appointments 
                               SET status = 'Accepted' 
                               WHERE appointment_id = '$appointmentId'");

if ($update) {
    echo "<p style='color:green;'>Appointment accepted successfully! Refreshing...</p>";
    header("refresh:2;url=appointment.php");
} else {
    echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    header("refresh:3;url=appointment.php");
}
?>
