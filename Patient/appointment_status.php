<?php
session_start();
include("../db.php");

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$current_username = $_SESSION['username'];

// 1️⃣ Get all appointments for this patient
$appointments = mysqli_query($conn, "SELECT * FROM appointments WHERE patient_username = '$current_username'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <style>
        .appointments-container {
            max-width: 900px;
            margin: 120px auto 50px;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .appointments-container h1 {
            text-align: center;
            color: var(--green);
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            text-align: left;
            font-size: 18px;
        }

        th, td {
            padding: 16px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: var(--green);
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 20px;
        }

        tr:hover {
            background: #f9f9f9;
        }

        h1 {
            font-size: xx-large;
        }
    </style>

<header class="header">
    <a href="./index.php" class="logo">
        <i class="fas fa-user"></i>
        <strong>CARE</strong>medical - Patient
    </a>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./appointment.php">Book Appointment</a>
        <a href="./appointment_status.php">Appointments Status</a>
        <a href="./logout.php" class="btn btn-danger" onclick="return confirm('Logout?')">Logout</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</header>

<h1 style="margin-top:100px; text-align:center;">My Appointments</h1>

<table>
    <tr>
        <th>Doctor</th>
        <th>Specialty</th>
        <th>Day</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php while ($appt = mysqli_fetch_assoc($appointments)): ?>
        <?php
        // 2️⃣ Get doctor details for each appointment
        $doc_username = $appt['doctor_username'];
        $doc_query = mysqli_query($conn, "SELECT full_name, specialist FROM doctors WHERE username = '$doc_username'");
        $doc = mysqli_fetch_assoc($doc_query);
        ?>
        <tr>
            <td>
                <?php 
                if ($doc) {
                    echo htmlspecialchars($doc['full_name']); 
                } else {
                    echo "<span style='color:red; font-weight:bold;'>Doctor Removed</span>";
                }
                ?>
            </td>
            <td>
                <?php 
                if ($doc) {
                    echo htmlspecialchars($doc['specialist']); 
                } else {
                    echo "<span style='color:gray;'>N/A</span>";
                }
                ?>
            </td>
            <td><?php echo htmlspecialchars($appt['appointment_day']); ?></td>
            <td><?php echo date("g:i A", strtotime($appt['appointment_time'])); ?></td>
            <td>
                <?php
                if (!$doc) {
                    // Doctor deleted → Cancelled
                    echo "<span style='color:red; font-weight:bold;'>Cancelled - Doctor Removed</span>";
                } else {
                    // Normal statuses
                    $status = strtolower(trim($appt['status']));
                    if ($status == 'accepted') {
                        echo "<span style='color:green;'>Accepted</span>";
                    } elseif ($status == 'pending') {
                        echo "<span style='color:orange;'>Pending</span>";
                    } else {
                        echo "<span style='color:red;'>Rejected</span>";
                    }
                }
                ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
