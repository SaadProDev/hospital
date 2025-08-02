<?php
include("../db.php");

if (isset($_GET['id'])) {
    $city_id = $_GET['id'];

    // Check if city exists
    $selectQuery = "SELECT * FROM cities WHERE city_id = $city_id";
    $selectResult = mysqli_query($conn, $selectQuery);

    if (mysqli_num_rows($selectResult) > 0) {
        // Proceed to delete
        $deleteQuery = "DELETE FROM cities WHERE city_id = $city_id";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "<script>alert('City deleted successfully!'); window.location.href='ViewCity.php';</script>";
        } else {
            echo "<script>alert('Error deleting city: " . mysqli_error($conn) . "'); window.location.href='ViewCity.php';</script>";
        }
    } else {
        echo "<script>alert('City not found!'); window.location.href='ViewCity.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Request!'); window.location.href='ViewCity.php';</script>";
}
?>
