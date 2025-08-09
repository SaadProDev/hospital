<?php
session_start();
include("db.php");

// ✅ Only allow patients
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'patient') {
    header("Location: login.php");
    exit();
}

$patientUsername = $_SESSION['username'];
$message = "";

// Keep selections
$selectedDoctor = $_POST['doctor'] ?? '';
$selectedDate   = $_POST['date'] ?? '';
$selectedTime   = $_POST['time'] ?? '';
$step = 1;

// Get doctors
$doctorResult = mysqli_query($conn, "SELECT username, full_name, specialist, city FROM doctors ORDER BY full_name");

// Step 2: Show dates
$dateOptions = [];
if (!empty($selectedDoctor)) {
    $step = 2;
    $availDays = [];
    $daysResult = mysqli_query($conn, "SELECT day_of_week FROM doctor_availability WHERE doctor_username = '$selectedDoctor'");
    while ($row = mysqli_fetch_assoc($daysResult)) {
        $availDays[] = $row['day_of_week'];
    }

    for ($i = 0; $i < 30; $i++) {
        $date = date('Y-m-d', strtotime("+$i days"));
        $dayName = date('l', strtotime($date));
        if (in_array($dayName, $availDays)) {
            $dateOptions[$date] = $dayName;
        }
    }
}

// Step 3: Show times
$timeOptions = [];
if (!empty($selectedDoctor) && !empty($selectedDate)) {
    $step = 3;
    $dayName = date('l', strtotime($selectedDate));
    $hoursResult = mysqli_query($conn, "SELECT start_time, end_time FROM doctor_availability 
                                        WHERE doctor_username = '$selectedDoctor' 
                                        AND day_of_week = '$dayName'");
    if ($hoursRow = mysqli_fetch_assoc($hoursResult)) {
        $startTime = strtotime($hoursRow['start_time']);
        $endTime   = strtotime($hoursRow['end_time']);

        for ($t = $startTime; $t <= $endTime; $t += 30 * 60) {
            $timeSlot = date('H:i:s', $t);
            $check = mysqli_query($conn, "SELECT 1 FROM appointments 
                                          WHERE doctor_username = '$selectedDoctor' 
                                          AND appointment_day = '$dayName'
                                          AND appointment_time = '$timeSlot'");
            if (mysqli_num_rows($check) === 0) {
                $timeOptions[] = $timeSlot;
            }
        }
    }
}

// Step 4: Book appointment
if (isset($_POST['book']) && !empty($selectedTime)) {
    $dayName = date('l', strtotime($selectedDate));
    $check = mysqli_query($conn, "SELECT 1 FROM appointments 
                                  WHERE doctor_username = '$selectedDoctor' 
                                  AND appointment_day = '$dayName'
                                  AND appointment_time = '$selectedTime'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<p style='color:red;'>That slot was just booked by someone else.</p>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO appointments 
                                       (doctor_username, patient_username, appointment_day, appointment_time, status) 
                                       VALUES ('$selectedDoctor', '$patientUsername', '$dayName', '$selectedTime', 'Pending')");
        if ($insert) {
            $message = "<p style='color:green;'>Appointment booked successfully!</p>";
            $step = 1;
            $selectedDoctor = $selectedDate = $selectedTime = '';
        } else {
            $message = "<p style='color:red;'>Database error: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="appointment.css">
        <link rel="stylesheet" href="../css/style.css" !important>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>

<header class="header">
    <a href="./index.php" class="logo">
        <i class="fas fa-user"></i>
        <strong>CARE</strong>medical - Patient
    </a>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./appointment.php">Book Appointment</a>
        <a href="./appointment_status.php">Appointment Status</a>
        <a href="./docprofile.php">Profile</a>
        <a href="./logout.php" class="btn btn-danger" onclick="return confirm('Logout?')">Logout</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</header>

<section class="appointment">
    <h1 class="heading" style="margin-top: 100px;"><span>Book</span> Appointment</h1>
    <?php echo $message; ?>

    <form method="POST" id="appointmentForm" class="box-container">
        
        <!-- Doctor Select -->
        <select name="doctor" class="box" onchange="document.getElementById('appointmentForm').submit()">
            <option value="">Select a doctor</option>
            <?php mysqli_data_seek($doctorResult, 0);
            while ($doc = mysqli_fetch_assoc($doctorResult)): ?>
                <option value="<?php echo $doc['username']; ?>" <?php if ($selectedDoctor == $doc['username']) echo 'selected'; ?>>
                    Dr. <?php echo $doc['full_name']; ?> - <?php echo $doc['specialist']; ?> (<?php echo $doc['city']; ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Date Select -->
        <?php if ($step >= 2): ?>
            <select name="date" class="box" onchange="document.getElementById('appointmentForm').submit()">
                <option value="">Select date</option>
                <?php foreach ($dateOptions as $date => $day): ?>
                    <option value="<?php echo $date; ?>" <?php if ($selectedDate == $date) echo 'selected'; ?>>
                        <?php echo $day . " - " . $date; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <!-- Time Select -->
        <?php if ($step >= 3): ?>
            <select name="time" class="box" required>
                <option value="">Select time</option>
                <?php foreach ($timeOptions as $time): ?>
                    <option value="<?php echo $time; ?>" <?php if ($selectedTime == $time) echo 'selected'; ?>>
                        <?php echo date('h:i A', strtotime($time)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <!-- One Button -->
        <?php if ($step < 3): ?>
            <input type="submit" value="Next" class="btn">
        <?php elseif ($step == 3): ?>
            <input type="submit" name="book" value="Book Appointment" class="btn">
        <?php endif; ?>
    </form>
</section>

</body>
</html>
