<?php
session_start();
include("../db.php"); // adjust path if needed

// ✅ Check login & role
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$username = trim($_SESSION['username']);

// ✅ Fetch this doctor's appointments with patient names
$sql = "
    SELECT 
        a.appointment_id,
        a.appointment_day,
        a.appointment_time,
        a.status,
        p.full_name AS patient_name
    FROM appointments a
    LEFT JOIN patients p ON a.patient_username = p.username
    WHERE a.doctor_username = '$username'
    ORDER BY FIELD(a.appointment_day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), a.appointment_time
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <style>
        /* ===============================
   Page Base Styling
   =============================== */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

/* ===============================
   Page Heading
   =============================== */
h2 {
    color: #16a085;
    text-align: center;
    margin: 40px 0 20px 0;
    font-size: 2.5rem; /* Bigger heading */
    text-transform: uppercase;
    font-weight: bold;
}

/* Optional underline style */
h2::after {
    content: '';
    display: block;
    margin: 10px auto 0;
    width: 80px;
    height: 4px;
    background-color: #16a085;
    border-radius: 2px;
}

/* ===============================
   Table Styling
   =============================== */
table {
    width: 95%;
    margin: 20px auto;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
}

/* Table headers & cells */
table th, table td {
    padding: 30px; /* Increased from 20px */
    text-align: center;
    font-size: 1.65rem; /* Increased from 1.1rem */
    border-bottom: 1px solid #ddd;
}

/* Table header row */
table th {
    background-color: #16a085;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    font-size: 1.8rem;
}

/* Hover effect for table rows */
table tr:hover {
    background-color: #f9f9f9;
}

/* No appointments row */
table td[colspan="5"] {
    font-style: italic;
    color: #777;
    padding: 40px;
    font-size: 1.4rem;
}

/* ===============================
   Appointment Status Colors
   =============================== */
td:nth-child(4) {
    font-weight: bold;
}

td.status-accepted {
    color: #27ae60;
}
td.status-rejected {
    color: #e74c3c;
}
td.status-pending {
    color: #f39c12;
}

/* ===============================
   Action Buttons
   =============================== */
table a {
    padding: 14px 24px; /* Bigger buttons */
    font-size: 1.3rem;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    display: inline-block;
    transition: background 0.3s ease, transform 0.2s ease;
}

/* Accept button */
table a[href*="accept"] {
    background-color: #27ae60;
    color: white;
}
table a[href*="accept"]:hover {
    background-color: #219150;
    transform: scale(1.05);
}

/* Reject button */
table a[href*="reject"] {
    background-color: #e74c3c;
    color: white;
}
table a[href*="reject"]:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* ===============================
   Responsive Design
   =============================== */
@media (max-width: 768px) {
    table, table th, table td {
        font-size: 1.2rem;
        padding: 15px;
    }
    h2 {
        font-size: 2rem;
    }
}

h2{
    margin-top: 15vh;
}
    </style>
    
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
<h2>My Appointments</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Patient Name</th>
        <th>Day</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['patient_name'] ?? 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($row['appointment_day']); ?></td>
                <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                   <a href="accept.php?id=<?php echo $row['appointment_id']; ?>">Accept</a> |
                   <a href="reject.php?id=<?php echo $row['appointment_id']; ?>">Reject</a>


                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">No appointments found</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
