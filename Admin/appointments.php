<?php
$conn = new mysqli('localhost','root','','hospital');
if ($conn->connect_error) { die('DB connection failed'); }

$sql = "
SELECT 
  a.appointment_id,
  a.appointment_day,
  a.appointment_time,
  a.status,
  COALESCE(p.full_name, a.patient_username) AS patient_name,
  d.full_name AS doctor_name,
  a.doctor_username
FROM appointments a
LEFT JOIN patients p ON a.patient_username = p.username
LEFT JOIN doctors  d ON a.doctor_username   = d.username
ORDER BY FIELD(a.appointment_day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
         a.appointment_time
";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Appointments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="admin-style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f5f5;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #33d9b2;
      color: white;
      padding: 20px;
      height: 100vh;
      position: fixed;
    }

    .sidebar h2 {
      font-size: 22px;
      margin-bottom: 30px;
      border-bottom: 2px solid white;
      padding-bottom: 10px;
    }

    .sidebar ul {
      list-style: none;
      padding-left: 0;
    }

    .sidebar ul li {
      margin: 15px 0;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .sidebar ul li a:hover {
      background-color: #2cc2a0;
      padding-left: 10px;
      color: black;
      font-weight: bold;
      border-radius: 6px;
    }

    /* Main */
    .main-content {
      margin-left: 250px;
      padding: 40px;
      background-color: #f5f5f5;
      min-height: 100vh;
    }

    .page-header {
      background: #33d9b2;
      color: white;
      font-size: 22px;
      font-weight: 600;
      padding: 15px 25px;
      border-radius: 8px;
      margin-bottom: 25px;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      border: none;
    }

    table th {
      color: #444;
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="admin-container">
  <!-- Sidebar -->
  <aside class="sidebar">
    <a href="./Admin.php"><h2>Admin Panel</h2></a>
    <ul>
      <li><a href="./AddCity.php">Add Cities</a></li>
      <li><a href="./adddoctor.php">Add Doctors</a></li>
      <li><a href="./AddPatients.php">Add Patients</a></li>
      <li><a href="./ViewCity.php">View Cities</a></li>
      <li><a href="./ReadDoctor.php">View Doctors</a></li>
      <li><a href="./ViewPatient.php">View Patients</a></li>
      <li><a href="./appointments.php">View Appointments</a></li>
      <li><a href="./logout.php">Logout</a></li>
    </ul>
  </aside>

  <!-- Main content -->
  <div class="main-content">
    <div class="page-header">View Appointments</div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Day</th>
                <th>Time</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($res && $res->num_rows > 0): ?>
                <?php $i=1; while($row = $res->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td>
                      <?php 
                        $doctor_deleted = empty($row['doctor_name']);
                        if ($doctor_deleted) {
                          echo "<span class='text-danger'>Doctor Deleted</span>";
                        } else {
                          echo htmlspecialchars($row['doctor_name']);
                        }
                      ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['appointment_day']); ?></td>
                    <td><?php echo date("g:i A", strtotime($row['appointment_time'])); ?></td>
                    <td>
                      <?php
                        if ($doctor_deleted) {
                          $status = "Cancelled";
                          $badge = "warning";
                        } else {
                          $status = $row['status'] ?? 'Pending';
                          $badge = 'secondary';
                          if (strcasecmp($status,'Accepted')===0) $badge = 'success';
                          elseif (strcasecmp($status,'Rejected')===0) $badge = 'danger';
                        }
                      ?>
                      <span class="badge text-bg-<?php echo $badge; ?>">
                        <?php echo htmlspecialchars($status); ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted">No appointments found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> <!-- main-content -->
</div> <!-- admin-container -->

<?php $conn->close(); ?>
</body>
</html>
