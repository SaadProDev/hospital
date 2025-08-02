<?php
include("../db.php");

if (isset($_GET['id'])) {
    $username = $_GET['id'];

    // Delete doctor record
    $deleteDoctor = "DELETE FROM doctors WHERE username = '$username'";
    $doctorDeleted = mysqli_query($conn, $deleteDoctor);

    // Delete user record
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
