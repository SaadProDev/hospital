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


    $deleteDoctor = "DELETE FROM doctors WHERE username = '$username'";
    $doctorDeleted = mysqli_query($conn, $deleteDoctor);


    $deleteUser = "DELETE FROM users WHERE username = '$username'";
    $userDeleted = mysqli_query($conn, $deleteUser);

    if ($doctorDeleted && $userDeleted) {
        echo "<script>alert('Doctor deleted successfully!'); window.location.href='ReadDoctor.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor!'); window.location.href='ReadDoctor.php';</script>";
    }
} else {
    echo "<script>alert('No Doctor ID Provided!'); window.location.href='ReadDoctor.php';</script>";
}
?>
