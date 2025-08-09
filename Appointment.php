<?php
session_start();
include("db.php");
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'patient') {
    header("Location: login.php");
    exit();
}
// Fetch doctors and cities
$doctorQuery = "SELECT username, full_name, specialist, city FROM doctors ORDER BY full_name";
$doctorResult = mysqli_query($conn, $doctorQuery);

$cityQuery = "SELECT city_name FROM cities ORDER BY city_name";
$cityResult = mysqli_query($conn, $cityQuery);

$availableTimes = [];
$selectedDoctor = $_POST['doctor'] ?? '';
$selectedDate = $_POST['date'] ?? '';
$selectedCity = $_POST['city'] ?? '';

/**
 * STEP 1: Generate available dates (server-side)
 */
$dateOptions = [];
if (!empty($selectedDoctor)) {
    $availDays = [];
    $dayQuery = "SELECT day_of_week FROM doctor_availability WHERE doctor_username = '$selectedDoctor'";
    $dayResult = mysqli_query($conn, $dayQuery);
    while ($row = mysqli_fetch_assoc($dayResult)) {
        $availDays[] = $row['day_of_week'];
    }

    if (!empty($availDays)) {
        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime("+$i days"));
            $dayName = date('l', strtotime($date));
            if (in_array($dayName, $availDays)) {
                $dateOptions[$date] = $dayName;
            }
        }
    }
}

/**
 * STEP 2: Show available times
 */
if (isset($_POST['show']) && !empty($selectedDoctor) && !empty($selectedDate)) {
    $day_name = date('l', strtotime($selectedDate));

    $availabilityQuery = "SELECT start_time, end_time 
                           FROM doctor_availability
                           WHERE doctor_username = '$selectedDoctor'
                           AND day_of_week = '$day_name'";
    $availabilityResult = mysqli_query($conn, $availabilityQuery);

    if ($row = mysqli_fetch_assoc($availabilityResult)) {
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);

        for ($time = $start; $time <= $end; $time += 30 * 60) {
            $formattedTime = date('H:i:s', $time);

            // Check if slot already booked
            $checkSlot = mysqli_query($conn, "SELECT * FROM appointments 
                WHERE doctor_username = '$selectedDoctor' 
                AND appointment_day = '$day_name'
                AND appointment_time = '$formattedTime'");
            if (mysqli_num_rows($checkSlot) == 0) {
                $availableTimes[] = $formattedTime;
            }
        }
    }
}

/**
 * STEP 3: Book appointment
 */
if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $doctorUsername = trim($_POST['doctor'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');

    // Required fields check
    if (empty($doctorUsername) || empty($date) || empty($time)) {
        $message = "<p style='color:red;'>Please select a doctor, date, and time.</p>";
    } else {
        $dayName = date('l', strtotime($date));
        $patientUsername = $_SESSION['username'] ?? null;

        // Prevent double booking
        $checkQuery = "SELECT 1 FROM appointments 
                       WHERE doctor_username = '$doctorUsername' 
                       AND appointment_day = '$dayName'
                       AND appointment_time = '$time'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $message = "<p style='color:red;'>This slot is already booked. Please choose another time.</p>";
        } else {
            // Insert booking
            $insertQuery = "INSERT INTO appointments 
                            (doctor_username, patient_username, appointment_day, appointment_time, status)
                            VALUES (
                                '$doctorUsername', 
                                " . ($patientUsername ? "'$patientUsername'" : "NULL") . ", 
                                '$dayName', '$time', 'Pending'
                            )";

            if (mysqli_query($conn, $insertQuery)) {
                $message = "<p style='color:green;'>Your appointment has been booked successfully!</p>";
            } else {
                $message = "<p style='color:red;'>Database Error: " . mysqli_error($conn) . "</p>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <a href="./index.php" class="logo"> <i class="fas fa-heartbeat"></i> <strong>CARE</strong>medical </a>
    <nav class="navbar">
        <a href="./index.php">home</a>
        <a href="./about.php">about</a>
        <a href="./doctors.php">doctors</a>
        <a href="./appointment.php">appointment</a>
        <a href="./news.php">news</a>
        <!-- <a href="admin/admin.php" class="admin-panel">admin panel</a> -->
        <a href="./login.php" class="log-in-button">Login</a>
    </nav>
    <div id="menu-btn" class="fas fa-bars"></div>
</header>

<section class="appointment" id="appointment">
    <h1 class="heading" style="margin-top: 100px;"> <span>appointment</span> now </h1>    

    <div class="row">
        <div class="image">
            <img src="image/appointment-img.svg" alt="">
        </div>

        <form method="POST">
            <h3>Make Appointment</h3>

            <?php if (!empty($message)) echo $message; ?>

            <input type="text" name="name" placeholder="your name" class="box"
                   value="<?php echo $_POST['name'] ?? ''; ?>">

            <input type="number" name="number" placeholder="your number" class="box"
                   value="<?php echo $_POST['number'] ?? ''; ?>">

            <input type="email" name="email" placeholder="your email" class="box"
                   value="<?php echo $_POST['email'] ?? ''; ?>">

            <select name="city" class="box">
                <option value="">Select your city</option>
                <?php mysqli_data_seek($cityResult, 0);
                while ($city = mysqli_fetch_assoc($cityResult)): ?>
                    <option value="<?php echo $city['city_name']; ?>"
                        <?php if ($selectedCity == $city['city_name']) echo 'selected'; ?>>
                        <?php echo $city['city_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="doctor" class="box" onchange="this.form.submit()">
                <option value="">Select a doctor</option>
                <?php mysqli_data_seek($doctorResult, 0);
                while ($doctor = mysqli_fetch_assoc($doctorResult)): ?>
                    <option value="<?php echo $doctor['username']; ?>"
                        <?php if ($selectedDoctor == $doctor['username']) echo 'selected'; ?>>
                        Dr. <?php echo $doctor['full_name']; ?> - <?php echo $doctor['specialist']; ?> (<?php echo $doctor['city']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="date" class="box" onchange="this.form.submit()">
                <option value="">Select date</option>
                <?php foreach ($dateOptions as $date => $dayName): ?>
                    <option value="<?php echo $date; ?>" 
                        <?php if ($selectedDate == $date) echo 'selected'; ?>>
                        <?php echo $dayName . ' - ' . $date; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($availableTimes)): ?>
                <select name="time" class="box" required>
                    <option value="">Select time</option>
                    <?php foreach ($availableTimes as $time): ?>
                        <option value="<?php echo $time; ?>">
                            <?php echo date('h:i A', strtotime($time)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php elseif (isset($_POST['show']) && empty($availableTimes)): ?>
                <p style="color:red;">No available times for this doctor on the selected date.</p>
            <?php endif; ?>

            <input type="submit" name="show" value="Show Available Times" class="btn">
            <input type="submit" name="submit" value="Book Appointment" class="btn">
        </form>
    </div>
</section>

</body>
</html>
