<?php
include("../db.php");

if (isset($_GET['id'])) {
    $city_id = $_GET['id'];

    // Fetch city details
    $selectQuery = "SELECT * FROM cities WHERE city_id = $city_id";
    $selectResult = mysqli_query($conn, $selectQuery);

    if (mysqli_num_rows($selectResult) > 0) {
        $cityData = mysqli_fetch_assoc($selectResult);
    } else {
        echo "<script>alert('City not found!'); window.location.href='ViewCities.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid Request!'); window.location.href='ViewCities.php';</script>";
    exit;
}

if (isset($_POST['btn'])) {
    $city_name = $_POST['city_name'];

    // Check if city already exists (but ignore current city)
    $checkQuery = "SELECT * FROM cities WHERE city_name = '$city_name' AND city_id != $city_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('City name already exists!');</script>";
    } else {
        $updateQuery = "UPDATE cities SET city_name = '$city_name' WHERE city_id = $city_id";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            echo "<script>alert('City updated successfully!'); window.location.href='ViewCities.php';</script>";
        } else {
            echo "<script>alert('Error updating city: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update City</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="admin-style.css">
  <style>
    /* Same CSS as before */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f5f5;
    }

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
    }

    .main-content {
      margin-left: 250px;
      flex: 1;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f5f5f5;
    }

    .city-form {
      width: 100%;
      max-width: 400px;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .city-form h3 {
      color: #33d9b2;
      font-weight: 700;
    }

    .city-form label {
      color: #444;
      font-weight: 500;
    }

    .btn-primary {
      background-color: #33d9b2;
      border: none;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #28c4a6;
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
        <li><a href="#">Add Patients</a></li>
        <li><a href="./ViewCities.php">View Cities</a></li>
        <li><a href="./ReadDoctor.php">View Doctors</a></li>
        <!-- Sidebar links -->
      </ul>
    </aside>

    <div class="main-content">
        <form method="POST" class="city-form">
            <h3 class="mb-4 text-center">Update City</h3>
            <div class="mb-3">
                <label class="form-label">City Name</label>
                <input type="text" class="form-control" name="city_name" value="<?php echo $cityData['city_name']; ?>" required>
            </div>
            <button type="submit" name="btn" class="btn btn-primary w-100">Update City</button>
        </form>
    </div>
</div>
</body>
</html>
