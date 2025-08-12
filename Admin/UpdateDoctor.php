<?php
session_start();
include("../db.php");

// ✅ Only admin can access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['id'])) {
    $username = $_GET['id'];

    $select = "SELECT * FROM doctors WHERE username = '$username'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) == 1) {
        $doctor = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Doctor not found!'); window.location.href='ReadDoctor.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No Doctor ID Provided!'); window.location.href='ReadDoctor.php';</script>";
    exit;
}

if (isset($_POST['btn'])) {
    $FullName = $_POST['FullName'];
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

    $updateQuery = "UPDATE doctors SET 
                    full_name = '$FullName',
                    email = '$Email',
                    phone = '$Phone',
                    specialist = '$Spec',
                    profile_description = '$Profile',
                    city = '$city_name'";

    // If new image uploaded
    if (!empty($pfp) && in_array($type, $allowedTypes) && $size <= 4000000) {
        move_uploaded_file($tmp_name, $folder);
        $updateQuery .= ", profile_photo = '$pfp'";
    }

    $updateQuery .= " WHERE username = '$username'";

    $run = mysqli_query($conn, $updateQuery);

    if ($run) {
        echo "<script>alert('Doctor updated successfully!'); window.location.href='ReadDoctor.php';</script>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Update Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Doctor</title>
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
        <li><a href="./logout.php">Logout</a></li>
      </ul>
    </aside>

    <div class="profile-card">
    <h3 class="mb-4 text-center">Update Doctor</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="text-center mb-4">
    <img src="../upload/<?php echo $doctor['profile_photo']; ?>" class="profile-img" alt="Profile Photo"><br>
    <label class="form-label">Profile Photo</label><br>
    <input type="file" name="imgupld"><br><br>
</div>

      <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="FullName" value="<?php echo $doctor['full_name']; ?>" required>

      <label class="form-label">Username</label>
            <input type="text" class="form-control" value="<?php echo $doctor['username']; ?>" readonly>

      <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Email" value="<?php echo $doctor['email']; ?>" required>

      <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="Phone" value="<?php echo $doctor['phone']; ?>" required>

      <label for="city_id" class="form-label">City</label>
       <select class="form-select" name="city_id" id="city_id" required>
                <option value="Lahore" <?php if($doctor['city'] == 'Lahore') echo 'selected'; ?>>Lahore</option>
                <option value="Karachi" <?php if($doctor['city'] == 'Karachi') echo 'selected'; ?>>Karachi</option>
                <option value="Islamabad" <?php if($doctor['city'] == 'Islamabad') echo 'selected'; ?>>Islamabad</option>
            </select> 

      <div class="mb-3">
        <label class="form-label">Specialist</label>
            <select class="form-select" name="speciality" required>
                <option value="Dermatology" <?php if($doctor['specialist'] == 'Dermatology') echo 'selected'; ?>>Dermatology</option>
                <option value="Oncologist" <?php if($doctor['specialist'] == 'Oncologist') echo 'selected'; ?>>Oncologist</option>
                <option value="Cardiologist" <?php if($doctor['specialist'] == 'Cardiologist') echo 'selected'; ?>>Cardiologist</option>
                <option value="Gastroenterology" <?php if($doctor['specialist'] == 'Gastroenterology') echo 'selected'; ?>>Gastroenterology</option>
                <option value="Neurologist" <?php if($doctor['specialist'] == 'Neurologist') echo 'selected'; ?>>Neurologist</option>
                <option value="Anesthesiology" <?php if($doctor['specialist'] == 'Anesthesiology') echo 'selected'; ?>>Anesthesiology</option>
                <option value="Psychiatry" <?php if($doctor['specialist'] == 'Psychiatry') echo 'selected'; ?>>Psychiatry</option>
                <option value="Family medicine" <?php if($doctor['specialist'] == 'Family medicine') echo 'selected'; ?>>Family medicine</option>
                <option value="Ophthalmologist" <?php if($doctor['specialist'] == 'Ophthalmologist') echo 'selected'; ?>>Ophthalmologist</option>
                <option value="Pediatrics" <?php if($doctor['specialist'] == 'Pediatrics') echo 'selected'; ?>>Pediatrics</option>
            </select>
      </div>

      <label class="form-label mt-3">Profile</label>
            <textarea class="form-control" name="Profile" rows="4" required><?php echo $doctor['profile_description']; ?></textarea>

      <button type="submit" name="btn" class="btn btn-primary w-100">Update Doctor</button>
    </form>
  </div>
  </div>

</body>
</html>
