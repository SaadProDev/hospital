<?php
session_start();
include("../db.php");

// ✅ Only admin can access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['id'])) {
    $username = $_GET['id'];


    $deletePatient = "DELETE FROM `patients` WHERE username = '$username'";
    $patientDeleted = mysqli_query($conn, $deletePatient);

    
    $deleteUser = "DELETE FROM users WHERE username = '$username'";
    $userDeleted = mysqli_query($conn, $deleteUser);

    if ($patientDeleted && $userDeleted) {
        echo "<script>alert('Patient deleted successfully!'); window.location.href='ViewPatient.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor!'); window.location.href='ViewPatient.php';</script>";
    }
} else {
    echo "<script>alert('No Patient ID Provided!'); window.location.href='ViewPatient.php';</script>";
}
?>
