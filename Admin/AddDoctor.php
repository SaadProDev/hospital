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
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $Spec = $_POST['speciality'];
    $Profile = $_POST['Profile'];
    $city_name = $_POST['city_id'];

    $pfp = $_FILES['imgupld']['name'];
    $tmp_name = $_FILES['imgupld']['tmp_name'];
    $size = $_FILES['imgupld']['size'];
    $type = strtolower($_FILES['imgupld']['type']);
    $folder = "../upload/" . $pfp;

    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    // Image upload validation
    if (!empty($pfp) && in_array($type, $allowedTypes) && $size <= 4000000) {
        move_uploaded_file($tmp_name, $folder);
    } else {
        $pfp = ""; // No image uploaded or invalid
    }

    // Insert into DB
    $insertQuery = "INSERT INTO doctors 
        (full_name, username, email, phone, specialist, profile_description, city, profile_photo)
        VALUES
        ('$FullName', '$Username', '$Email', '$Phone', '$Spec', '$Profile', '$city_name', '$pfp')";

    $run = mysqli_query($conn, $insertQuery);

    if ($run) {
        echo "<script>alert('Doctor added successfully!'); window.location.href='ReadDoctor.php';</script>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Insert Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Doctor</title>
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
        <li><a href="./adddoctor.php" class="active">Add Doctors</a></li>
        <li><a href="./AddPatients.php">Add Patients</a></li>
        <li><a href="./ViewCity.php">View Cities</a></li>
        <li><a href="./ReadDoctor.php">View Doctors</a></li>
        <li><a href="./ViewPatient.php">View Patients</a></li>
        <li><a href="#">Manage Logins</a></li>
      </ul>
    </aside>

    <div class="profile-card">
    <h3 class="mb-4 text-center">Add Doctor</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3 text-center">
        <label class="form-label">Profile Photo</label><br>
        <input type="file" name="imgupld">
      </div>

      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="FullName" required>

      <label class="form-label">Username</label>
      <input type="text" class="form-control" name="Username" required>

      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="Email" required>

      <label class="form-label">Phone</label>
      <input type="text" class="form-control" name="Phone" required>

      <label for="city_id" class="form-label">City</label>
      <select class="form-select" name="city_id" id="city_id" required>
        <option value="">Select City</option>
        <option value="Lahore">Lahore</option>
        <option value="Karachi">Karachi</option>
        <option value="Islamabad">Islamabad</option>
      </select> 

      <div class="mb-3">
        <label class="form-label">Specialist</label>
        <select class="form-select" name="speciality" required>
          <option value="">Select Speciality</option>
          <option value="Dermatology">Dermatology</option>
          <option value="Oncologist">Oncologist</option>
          <option value="Cardiologist">Cardiologist</option>
          <option value="Gastroenterology">Gastroenterology</option>
          <option value="Neurologist">Neurologist</option>
          <option value="Anesthesiology">Anesthesiology</option>
          <option value="Psychiatry">Psychiatry</option>
          <option value="Family medicine">Family medicine</option>
          <option value="Ophthalmologist">Ophthalmologist</option>
          <option value="Pediatrics">Pediatrics</option>
        </select>
      </div>

      <label class="form-label mt-3">Profile</label>
      <textarea class="form-control" name="Profile" rows="4" required></textarea>

      <button type="submit" name="btn" class="btn btn-success w-100">Add Doctor</button>
    </form>
  </div>
</div>

</body>
</html>
