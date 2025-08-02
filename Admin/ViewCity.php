<?php
include("../db.php");

$fetchCities = "SELECT * FROM cities";
$run = mysqli_query($conn, $fetchCities);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Cities</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin-style.css">
  <link rel="stylesheet" href="RDoc.css">
  <style>
    .card {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .card-body {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .city-name {
      font-size: 1.5rem;
      font-weight: 700;
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <aside class="sidebar">
      <a href="./Admin.php"><h2>Admin Panel</h2></a>
      <ul>
        <li><a href="./AddCities.php">Add Cities</a></li>
        <li><a href="./adddoctor.php">Add Doctors</a></li>
        <li><a href="#">Add Patients</a></li>
        <li><a href="./ViewCities.php">View Cities</a></li>
        <li><a href="./ReadDoctor.php">View Doctors</a></li>
        <li><a href="#">View Patients</a></li>
        <!-- other options -->
      </ul>
    </aside>

    <div class="main-content">
      <header>
        <h2>View Cities</h2>
      </header>

      <div class="content-area">
        <div class="row">
          <?php while($city = mysqli_fetch_assoc($run)){ ?>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-4">
              <div class="card h-100 text-center">
                <div class="card-body">
                  <h5 class="city-name"><?php echo $city['city_name'] ?></h5>
                  <div>
                    <a href="UpdateCity.php?id=<?php echo $city['city_id']?>" class="btn btn-primary mt-2">Update</a>
                    <a href="DeleteCity.php?id=<?php echo $city['city_id']?>" class="btn btn-danger mt-2 ms-2" onclick="return confirm('Are you sure you want to delete this city?')">Delete</a>
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
