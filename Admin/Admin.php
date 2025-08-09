<?php

  session_start();
include("../db.php");

// ✅ Only admin can access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctors</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin-style.css">
  <link rel="stylesheet" href="RDoc.css">
</head>
<body>
  <div class="admin-container">
    <aside class="sidebar">
      <a href="./Admin.php"><h2>Admin Panel</h2></a>
      <ul>
        <li><a href="./AddCity.php">Add Cities</a></li>
        <li><a href="./adddoctor.php">Add Doctors</a></li>
        <li><a href="./AddPatients.php">Add Patients</a></li>
        <li><a href="./ViewCity.php">View Cities</a></li>
        <li><a href="./ReadDoctor.php">View Doctors</a></li>
        <li><a href="./ViewPatient.php">View Patients</a></li>
      </ul>
    </aside>

    <main class="main-content">
      <header>
        <h1>Welcome, <span>ADMIN</span></h1>
      </header>
      <section class="content-area">
        <p>Select an option from the sidebar to manage the system.</p>
      </section>
    </main>
  </div>
</body>

</html>

