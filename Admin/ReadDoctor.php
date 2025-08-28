<?

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
  <style>
    /* You can add custom CSS here if needed */
  </style>
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
        <li><a href="./appointments.php">View Appointments</a></li>
        <li><a href="./logout.php">Logout</a></li>
      </ul>
    </aside>

    <div class="main-content">
      <header>
        <h2>View Doctors</h2>
      </header>

      <div class="content-area">
        <div class="row">
          <?php
            include("../db.php");
            $slc = "SELECT * FROM doctors";
            $run = mysqli_query($conn, $slc);

            while($arr = mysqli_fetch_assoc($run)){ ?>
              <div class="card h-100 text-center d-flex flex-column justify-content-center align-items-center">
  <img src="../upload/<?php echo $arr['profile_photo'] ?>" 
       onerror="this.onerror=null;this.src='default.jpg';" 
       class="doctor-img mb-3" 
       alt="Doctor Image">
  <div class="card-body d-flex flex-column justify-content-center align-items-center">
    <h5 class="card-title"><?php echo $arr['full_name'] ?></h5>
    <p class="card-text"><?php echo $arr['specialist'] ?></p>
    <p class="card-text"><?php echo $arr['phone'] ?></p>
    <p class="card-text"><?php echo $arr['email'] ?></p>
    <p class="card-text"><?php echo $arr['profile_description'] ?></p>
    <div>
      <a href="UpdateDoctor.php?id=<?php echo $arr['username']?>" class="btn btn-primary mt-2">Update</a>
      <a href="DeleteDoctor.php?id=<?php echo $arr['username']?>" class="btn btn-danger mt-2 ms-2" onclick="return confirm('Are you sure?')">Delete</a>
    </div>
  </div>
</div>

          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
