<?php
session_start();
include("../db.php");

// ✅ Only admin can access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_POST['btn'])) {
    $FullName = $_POST['FullName'];
    $username = $_POST['UserName'];
    $password = $_POST['Password'];
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $city_name = $_POST['city_name'];

    $pfp = $_FILES['imgupld']['name'];
    $type = strtolower($_FILES['imgupld']['type']);
    $tmp_name = $_FILES['imgupld']['tmp_name'];
    $size = $_FILES['imgupld']['size'];
    $folder = "../upload/" . $pfp;

    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $userExists = mysqli_query($conn, $checkUser);

    $checkEmail = "SELECT * FROM patients WHERE email = '$Email'";
    $emailExists = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($userExists) > 0 || mysqli_num_rows($emailExists) > 0) {
        echo "<script>alert('Username or Email already exists. Please choose another.');</script>";
        exit;
    }

    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    if (in_array($type, $allowedTypes)) {
        if ($size <= 4000000) {
            $userInsert = "INSERT INTO users(username, password, role) VALUES ('$username', '$password', 'Patient')";
            $userRun = mysqli_query($conn, $userInsert);

            if ($userRun) {
                $insert = "INSERT INTO patients(username, full_name, email, phone, city, profile_photo)
                           VALUES ('$username', '$FullName', '$Email', '$Phone', '$city_name', '$pfp')";

                $run = mysqli_query($conn, $insert);

                if ($run) {
                    move_uploaded_file($tmp_name, $folder);
                    echo "<div class='alert alert-success mt-3'>Patient added successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Patient Insert Error: " . mysqli_error($conn) . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3'>User Insert Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<script>alert('Image size too large. Max 4MB allowed.')</script>";
        }
    } else {
        echo "<script>alert('Image type not supported. Only JPG, JPEG, PNG allowed.')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Patient</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="admin-style.css">
  <link rel="stylesheet" href="RDoc.css">
  <link rel="stylesheet" href="adDoc.css" defer>
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

    <div class="profile-card">
        <h3 class="mb-4 text-center">Add Patient</h3>

        <form method="POST" enctype="multipart/form-data">
            <div class="text-center mb-4">
                <img src="" class="profile-img" alt="Profile Photo"><br>
                <label class="form-label">Profile Photo</label><br>
                <input type="file" name="imgupld" required><br><br>
            </div>

            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="FullName" required>

            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="UserName" placeholder="Username cannot be changed once set" required>

            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="Password" required>

            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Email" required>

            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="Phone" required>

            <label for="city_name" class="form-label">City</label>
            <select class="form-select" name="city_name" id="city_name" required>
                <option value="" disabled selected>Select your city</option>
                <?php
                    $cityQuery = "SELECT city_name FROM cities";
                    $cityResult = mysqli_query($conn, $cityQuery);
                    while ($cityRow = mysqli_fetch_assoc($cityResult)) {
                        echo "<option value='" . $cityRow['city_name'] . "'>" . $cityRow['city_name'] . "</option>";
                    }
                ?>
            </select> 

            <button type="submit" name="btn" class="btn btn-primary w-100 mt-4">Add Patient</button>
        </form>
    </div>
</div>
</body>
</html>
