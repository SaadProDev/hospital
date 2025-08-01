<?php
include("../db.php");
if (isset($_POST['btn'])) {
    $FullName = $_POST['FullName'];
    $username = $_POST['UserName'];
    $password = $_POST['Password'];
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $Spec = $_POST['speciality'];
    $Profile = $_POST['Profile'];
    $city_id = $_POST['city_id'];

    $pfp = $_FILES['imgupld']['name'];
    $type = strtolower($_FILES['imgupld']['type']);
    $tmp_name = $_FILES['imgupld']['tmp_name'];
    $size = $_FILES['imgupld']['size'];
    $folder = "../upload/" . $pfp;

    $role_id = 2;

    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $userExists = mysqli_query($conn, $checkUser);

    $checkEmail = "SELECT * FROM doctors WHERE email = '$Email'";
    $emailExists = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($userExists) > 0) {
        echo "<script>
    alert('Username already exists. Please choose another one.')
</script>";
        exit;
    }

    if (mysqli_num_rows($emailExists) > 0) {
        echo "<script>
    alert('Username already exists. Please choose another one.')
</script>";
        exit;
    }

    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    if (in_array($type, $allowedTypes)) {
        if ($size <= 4000000) {
            $userInsert = "INSERT INTO users(username, password, role_id) VALUES ('$username', '$password', $role_id)";
            $userRun = mysqli_query($conn, $userInsert);

            if ($userRun) {
                $user_id = mysqli_insert_id($conn);

                $insert = "INSERT INTO doctors(user_id, full_name, email, phone, specialist, profile, city_id, ProfilePhoto)
                           VALUES ($user_id, '$FullName', '$Email', '$Phone', '$Spec', '$Profile', $city_id, '$pfp')";

                $run = mysqli_query($conn, $insert);

                if ($run) {
                    move_uploaded_file($tmp_name, $folder);
                    echo "<div class='alert alert-success mt-3'>Doctor added successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Doctor Insert Error: " . mysqli_error($conn) . "</div>";
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
  <title>Doctor Profile</title>
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
        <li><a href="#">Add Cities</a></li>
        <li><a href="./adddoctor.php">Add Doctors</a></li>
        <li><a href="#">Add Patients</a></li>
        <li><a href="#">View Cities</a></li>
        <li><a href="./ReadDoctor.php">View Doctors</a></li>
        <li><a href="#">View Patients</a></li>
        <li><a href="#">Modify Cities</a></li>
        <li><a href="#">Modify Doctor Details</a></li>
        <li><a href="#">Modify Patient Details</a></li>
        <li><a href="#">Delete Cities</a></li>
        <li><a href="#">Delete Doctors</a></li>
        <li><a href="#">Delete Patients</a></li>
        <li><a href="#">Manage Logins</a></li>
      </ul>
    </aside>

    <div class="profile-card">
    <h3 class="mb-4 text-center">Add Doctor</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="text-center mb-4">
        <img src="<?php echo $folder ?>" class="profile-img" alt="Profile Photo">
        <label class="form-label">Profile Photo</label><br>
            <input type="file" name="imgupld" required><br><br>
      </div>

      <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="FullName" required>

      <label class="form-label">Username</label>
            <input type="text" class="form-control" name="UserName" required>


       <label class="form-label">Password</label>
            <input type="password" class="form-control" name="Password" required>

      <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Email" required>

      <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="Phone" required>

       <label for="city_id" class="form-label">City</label>
       <select class="form-select" name="city_id" id="city_id" required>
                <option value="" disabled selected>Select your city</option>
                <option value="1">Lahore</option>
                <option value="2">Karachi</option>
                <option value="3">Islamabad</option>
            </select> 

      <div class="mb-3">
        <label class="form-label">Specialist</label>
            <select class="form-select" name="speciality" required>
                <option value="" disabled selected>Select a specialty</option>
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

      <<label class="form-label mt-3">Profile</label>
            <textarea class="form-control" name="Profile" rows="4" required></textarea>

      <button type="submit" name="btn" class="btn btn-primary w-100">Add Doctor</button>
    </form>
  </div>
  </div>

</body>
</html>

