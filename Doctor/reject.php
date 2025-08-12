<?php
session_start();
include("../db.php");

// ✅ Check login & role
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'doctor') {
    exit("Access denied");
}

$doctorUsername = trim($_SESSION['username']);
$appointmentId  = $_GET['id'] ?? null;

if (!$appointmentId) {
    exit("Invalid appointment ID.");
}

// ✅ Ensure appointment belongs to this doctor
$check = mysqli_query($conn, "SELECT 1 FROM appointments WHERE appointment_id = '$appointmentId' AND doctor_username = '$doctorUsername'");
if (mysqli_num_rows($check) === 0) {
    exit("Unauthorized action.");
}

// Update status
if (mysqli_query($conn, "UPDATE appointments SET status = 'Rejected' WHERE appointment_id = '$appointmentId'")) {
    echo "<p style='color:green;'>Appointment rejected successfully! Refreshing...</p>";
    echo '<meta http-equiv="refresh" content="2;url=appointment.php">';
} else {
    echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    echo '<meta http-equiv="refresh" content="3;url=appointment.php">';
}
?>
