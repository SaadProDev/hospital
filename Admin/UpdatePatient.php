<?php
session_start();
include("../db.php");

// ✅ Only admin can access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['id'])) {
    $current_username = $_GET['id'];

    $selectQuery = "SELECT * FROM patients WHERE username = '$current_username'";
    $result = mysqli_query($conn, $selectQuery);

    if (mysqli_num_rows($result) == 1) {
        $patient = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Patient not found!');window.location.href='ViewPatients.php';</script>";
        exit;
    }
}

if (isset($_POST['btn'])) {
    $new_username = $_POST['UserName'];
    $FullName     = $_POST['FullName'];
    $Email        = $_POST['Email'];
    $Phone        = $_POST['Phone'];
    $City         = $_POST['city_name'];
    $Password     = $_POST['Password']; // New password

    // Check for duplicate username in users (excluding current)
    $checkUser = "SELECT * FROM users WHERE username = '$new_username' AND username != '$current_username'";
    $userExists = mysqli_query($conn, $checkUser);

    if (mysqli_num_rows($userExists) > 0) {
        echo "<script>alert('Username already exists. Choose another.');</script>";
        exit;
    }

    // Handle profile photo upload (optional)
    if (!empty($_FILES['imgupld']['name'])) {
        $pfp      = $_FILES['imgupld']['name'];
        $tmp_name = $_FILES['imgupld']['tmp_name'];
        $type     = strtolower($_FILES['imgupld']['type']);
        $size     = $_FILES['imgupld']['size'];
        $folder   = "../upload/" . $pfp;

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

        if (in_array($type, $allowedTypes)) {
            if ($size <= 4000000) {
                move_uploaded_file($tmp_name, $folder);
                $updatePatient = "UPDATE patients SET 
                    username = '$new_username', 
                    full_name = '$FullName', 
                    email = '$Email', 
                    phone = '$Phone', 
                    city = '$City', 
                    profile_photo = '$pfp' 
                    WHERE username = '$current_username'";
            } else {
                echo "<script>alert('Image size too large. Max 4MB allowed.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Invalid image format. Only JPG, JPEG, PNG allowed.');</script>";
            exit;
        }
    } else {
        // Update without changing profile photo
        $updatePatient = "UPDATE patients SET 
            username = '$new_username', 
            full_name = '$FullName', 
            email = '$Email', 
            phone = '$Phone', 
            city = '$City' 
            WHERE username = '$current_username'";
    }

    // Update Users Table
    if (!empty($Password)) {
        $updateUser = "UPDATE users SET username = '$new_username', password = '$Password' WHERE username = '$current_username'";
    } else {
        $updateUser = "UPDATE users SET username = '$new_username' WHERE username = '$current_username'";
    }

    $runUser    = mysqli_query($conn, $updateUser);
    $runPatient = mysqli_query($conn, $updatePatient);

    if ($runUser && $runPatient) {
        echo "<script>alert('Patient updated successfully!'); window.location.href='ViewPatients.php';</script>";
    } else {
        echo "<script>alert('Error updating patient: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Patient</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="admin-style.css">
  <link rel="stylesheet" href="RDoc.css">
  <link rel="stylesheet" href="adDoc.css">
  <style>
    .profile-card {
      width: 100%;
      max-width: 500px;
      margin: auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .profile-img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #33d9b2;
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
        <li><a href="./logout.php">Logout</a></li>
      </ul>
    </aside>

    <div class="profile-card">
      <h3 class="mb-4 text-center">Update Patient</h3>
      <form method="POST" enctype="multipart/form-data">
        <!-- <div class="text-center mb-4">
          <img src="../upload/<?php echo $patient['profile_photo']; ?>" 
               onerror="this.onerror=null;this.src='default.jpg';" 
               class="profile-img" alt="Profile Photo"><br>
          <label class="form-label">Profile Photo</label><br>
          <input type="file" name="imgupld"><br><br>
        </div> -->

        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="FullName" value="<?php echo $patient['full_name']; ?>" required>

        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="UserName" value="<?php echo $patient['username']; ?>" required>

        <label class="form-label">Password <small>(Leave blank to keep existing)</small></label>
        <input type="password" class="form-control" name="Password">

        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="Email" value="<?php echo $patient['email']; ?>" required>

        <label class="form-label">Phone</label>
        <input type="text" class="form-control" name="Phone" value="<?php echo $patient['phone']; ?>" required>

        <label class="form-label">City</label>
<select name="city_name" class="form-control" required>
    <option value="" disabled>Select your city</option>
    <option value="Abbottabad" <?php if($patient['city'] == 'Abbottabad') echo 'selected'; ?>>Abbottabad</option>
    <option value="Bahawalpur" <?php if($patient['city'] == 'Bahawalpur') echo 'selected'; ?>>Bahawalpur</option>
    <option value="Karachi" <?php if($patient['city'] == 'Karachi') echo 'selected'; ?>>Karachi</option>
    <option value="Lahore" <?php if($patient['city'] == 'Lahore') echo 'selected'; ?>>Lahore</option>
    <option value="Multan" <?php if($patient['city'] == 'Multan') echo 'selected'; ?>>Multan</option>
</select>


        <button type="submit" name="btn" class="btn btn-primary w-100 mt-4">Update Patient</button>
      </form>
    </div>
</div>
</body>
</html>
