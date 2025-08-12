<?php
session_start();
include("../db.php");

// ✅ Only allow patients
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

// ✅ Fetch patient details
$sql = "SELECT full_name, profile_photo, city 
        FROM patients 
        WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$patient = mysqli_fetch_assoc($result);

$patient_name = $patient['full_name'] ?? "Patient";
$patient_photo = !empty($patient['profile_photo']) ? "../upload/" . $patient['profile_photo'] : "../image/default.jpg";
$patient_city = $patient['city'] ?? "Unknown City";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - CARE Medical</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- header section -->
<header class="header">
    <a href="./index.php" class="logo">
        <i class="fas fa-user"></i>
        <strong>CARE</strong>medical - Patient
    </a>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./appointment.php">Book Appointment</a>
        <a href="./appointment_status.php">Appointments Status</a>
        <!-- <a href="./patientprofile.php">Profile</a> -->
        <a href="./logout.php" class="btn btn-danger" onclick="return confirm('Logout?')">Logout</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</header>

<!-- home section -->
<section class="home" id="home">
    <div class="image">
        <img src="../upload/downlod (1).jpeg" 
             alt="<?php echo htmlspecialchars($patient_name); ?>" 
             style="width: 60%; height: 60%; object-fit: cover; border-radius: 50%;">
    </div>

    <div class="content">
        <h3>Welcome, <?php echo htmlspecialchars($patient_name); ?>!</h3>
        <p>City: <?php echo htmlspecialchars($patient_city); ?></p>
        <p> Book appointments, track your visits, and manage your health information easily.</p>
        <a href="./appointment.php" class="btn"> Book Now <span class="fas fa-chevron-right"></span> </a>
    </div>
</section>

<!-- icons -->
<section class="icons-container">
    <div class="icons">
        <i class="fas fa-calendar-check"></i>
        <h3>Next</h3>
        <p>appointment</p>
    </div>

    <div class="icons">
        <i class="fas fa-user-md"></i>
        <h3>50+</h3>
        <p>available doctors</p>
    </div>

    <div class="icons">
        <i class="fas fa-hospital"></i>
        <h3>10+</h3>
        <p>partner hospitals</p>
    </div>

    <div class="icons">
        <i class="fas fa-notes-medical"></i>
        <h3>View</h3>
        <p>medical history</p>
    </div>
</section>

<!-- footer -->
<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>quick links</h3>
            <a href="./index.php"><i class="fas fa-chevron-right"></i> home </a>
            <a href="./appointment.php"><i class="fas fa-chevron-right"></i> appointment </a>
            <a href="./my_appointments.php"><i class="fas fa-chevron-right"></i> my appointments </a>
            <a href="./docprofile.php"><i class="fas fa-chevron-right"></i> profile </a>
        </div>

        <div class="box">
            <h3>support</h3>
            <a href="#"><i class="fas fa-chevron-right"></i> help center </a>
            <a href="#"><i class="fas fa-chevron-right"></i> contact us </a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#"><i class="fas fa-phone"></i> +923071338783 </a>
            <a href="#"><i class="fas fa-envelope"></i> support@caremedical.com </a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> karachi, Pakistan </a>
        </div>
    </div>
    <div class="credit"> created by <span>Data Drifters</span></div>
</section>

<script src="../js/script.js"></script>
</body>
</html>
