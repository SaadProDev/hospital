<?php
session_start();
include("db.php");

// Make sure the doctor is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
    exit;
}

$doctor_username = $_SESSION['username'];

// Get all appointments for this doctor
$sql = "SELECT * FROM appointments 
        WHERE doctor_username = '$doctor_username' 
        ORDER BY appointment_day, appointment_time";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Appointments</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: center;
        }
        th {
            background-color: #33d9b2;
            color: white;
        }
        a {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .accept {
            background-color: green;
        }
        .reject {
            background-color: red;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Manage Appointments</h2>

<table>
    <tr>
        <th>Patient Name</th>
        <th>Day</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { 
        $patient_username = $row['patient_username'];

        // Get patient name
        $patient_result = mysqli_query($conn, "SELECT full_name FROM patients WHERE username = '$patient_username'");
        $patient = mysqli_fetch_assoc($patient_result);
        $patient_name = $patient ? $patient['full_name'] : 'Unknown';

        // Format time to 12-hour
        $formatted_time = date("g:i A", strtotime($row['appointment_time']));
    ?>
        <tr>
            <td><?php echo $patient_name; ?></td>
            <td><?php echo $row['appointment_day']; ?></td>
            <td><?php echo $formatted_time; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <a href="update_appointment.php?id=<?php echo $row['appointment_id']; ?>&status=Accepted" class="accept">Accept</a>
                <a href="update_appointment.php?id=<?php echo $row['appointment_id']; ?>&status=Rejected" class="reject">Reject</a>
            </td>
        </tr>
    <?php } ?>

</table>

</body>
</html>
