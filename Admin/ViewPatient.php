<?php
include("../db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Patients</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin-style.css">
  <link rel="stylesheet" href="RDoc.css">
  <style>
    .doctor-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #33d9b2;
    }

    .card {
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .card-body h5 {
      font-weight: 700;
    }

    .btn-primary {
      background-color: #33d9b2;
      border: none;
    }

    .btn-primary:hover {
      background-color: #28c4a6;
    }

    .btn-danger:hover {
      background-color: #e60023;
    }
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
        <li><a href="#">Manage Logins</a></li>
      </ul>
    </aside>

    <div class="main-content">
      <header>
        <h2>View Patients</h2>
      </header>

      <div class="content-area">
        <div class="row">
          <?php
            $slc = "SELECT * FROM patients";
            $run = mysqli_query($conn, $slc);

            while($arr = mysqli_fetch_assoc($run)){ ?>
              <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 text-center d-flex flex-column justify-content-center align-items-center">
                  <img src="../upload/<?php echo $arr['profile_photo'] ?>" 
                      onerror="this.onerror=null;this.src='default.jpg';" 
                      class="doctor-img mb-3" 
                      alt="Patient Image">
                  <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title"><?php echo $arr['full_name'] ?></h5>
                    <p class="card-text"><?php echo $arr['email'] ?></p>
                    <p class="card-text"><?php echo $arr['phone'] ?></p>
                    <p class="card-text"><?php echo $arr['city'] ?></p>
                    <div>
                      <a href="UpdatePatient.php?id=<?php echo $arr['username']?>" class="btn btn-primary mt-2">Update</a>
                      <a href="DeletePatient.php?id=<?php echo $arr['username']?>" class="btn btn-danger mt-2 ms-2" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
                    </div>
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
