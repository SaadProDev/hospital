<?php
session_start();
include("db.php");

// Optional: restrict access to only doctors
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../login.php");
    exit();
}

// Get doctor's profile information from database
$doctor_username = $_SESSION['username'];
$doctor_query = "SELECT full_name, profile_photo, specialist FROM doctors WHERE username = '$doctor_username'";
$doctor_result = mysqli_query($conn, $doctor_query);

// Set default values
$doctor_photo = "../upload/download (1)"; // Default photo path
$doctor_name = $_SESSION['username']; // Fallback to username
$doctor_specialty = "General Practitioner"; // Default specialty

// If doctor found in database, use their information
if ($doctor_result && mysqli_num_rows($doctor_result) > 0) {
    $doctor_data = mysqli_fetch_assoc($doctor_result);
    
    // Use full name if available, otherwise use username
    if (!empty($doctor_data['full_name'])) {
        $doctor_name = $doctor_data['full_name'];
    }
    
    // Use profile photo if available and file exists
    if (!empty($doctor_data['profile_photo'])) {
        $photo_path = "../upload/" . $doctor_data['profile_photo']; // Adjust path as needed
        if (file_exists($photo_path)) {
            $doctor_photo = $photo_path;
        }
    }
    
    // Use specialty if available
    if (!empty($doctor_data['specialist'])) {
        $doctor_specialty = $doctor_data['specialist'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - CARE Medical</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    
<!-- header section starts  -->

<header class="header">

    <a href="./index.php" class="logo"> 
        <i class="fas fa-stethoscope"></i> 
        <strong>CARE</strong>medical - Doctor 
    </a>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./appointment.php">My Appointments</a>
        <a href="./availability.php">Set Availability</a>
        <a href="./docprofile.php">Profile</a>
        <a href="./logout.php" class="btn btn-danger" 
   onclick="return confirm('Are you sure you want to logout?')">
   Logout
</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>

</header>

<!-- home section starts  -->

<section class="home" id="home">

    <div class="image">
        <img src="<?php echo htmlspecialchars($doctor_photo); ?>" alt="Dr. <?php echo htmlspecialchars($doctor_name); ?>" style="width: 60%; height: 60%; object-fit: cover; border-radius: 50%;">
    </div>

    <div class="content">
        <h3>Welcome, Dr. <?php echo htmlspecialchars($doctor_name); ?>!</h3>
        <h1><u><?php echo htmlspecialchars($doctor_specialty); ?></u></h1>
        <p> Manage your appointments, update your availability, and connect with patients efficiently.</p>
        <a href="./appointment.php" class="btn"> View Appointments <span class="fas fa-chevron-right"></span> </a>
    </div>

</section>

<!-- icons section starts  -->

<section class="icons-container">

    <div class="icons">
        <i class="fas fa-calendar-check"></i>
        <h3>20+</h3>
        <p>appointments this week</p>
    </div>

    <div class="icons">
        <i class="fas fa-user-injured"></i>
        <h3>150+</h3>
        <p>patients treated</p>
    </div>

    <div class="icons">
        <i class="fas fa-hospital-user"></i>
        <h3>10+</h3>
        <p>hospital visits</p>
    </div>

    <div class="icons">
        <i class="fas fa-clock"></i>
        <h3>Set</h3>
        <p>your availability</p>
    </div>

</section>

<!-- review section (can be replaced with doctor notes or tips) -->

<section class="review" id="review">
    
    <h1 class="heading"> latest <span>patient feedback</span> </h1>

    <div class="box-container">

        <div class="box">
            <img src="../image/pic-1.jpg" alt="">
            <h3>John Doe</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <p class="text">Doctor was very attentive and explained everything clearly.</p>
        </div>

        <div class="box">
            <img src="../image/pic-2.jpg" alt="">
            <h3>Jane Smith</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <p class="text">Very professional and kind during my treatment.</p>
        </div>

    </div>

</section>

<!-- footer section starts  -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3>quick links</h3>
            <a href="./doctor_home.php"> <i class="fas fa-chevron-right"></i> home </a>
            <a href="./my_appointments.php"> <i class="fas fa-chevron-right"></i> appointments </a>
            <a href="./availability.php"> <i class="fas fa-chevron-right"></i> availability </a>
            <a href="./patients_list.php"> <i class="fas fa-chevron-right"></i> patients </a>
            <a href="./profile.php"> <i class="fas fa-chevron-right"></i> profile </a>
        </div>

        <div class="box">
            <h3>support</h3>
            <a href="#"> <i class="fas fa-chevron-right"></i> help center </a>
            <a href="#"> <i class="fas fa-chevron-right"></i> contact admin </a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#"> <i class="fas fa-phone"></i> +8801688238801 </a>
            <a href="#"> <i class="fas fa-envelope"></i> support@caremedical.com </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> sylhet, bangladesh </a>
        </div>

    </div>

    <div class="credit"> created by <span>Data Drifters</span></div>

</section>

<!-- footer section ends -->

<script src="../js/script.js"></script>

</body>
</html>