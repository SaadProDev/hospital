<?php
include("../db.php");

// Fetch cities from the database
$cityQuery = "SELECT * FROM cities";
$cityResult = mysqli_query($conn, $cityQuery);

if (isset($_POST['btn'])) {
    $FullName = $_POST['FullName'];
    $username = $_POST['UserName'];
    $password = $_POST['Password'];
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $Spec = $_POST['speciality'];
    $Profile = $_POST['Profile'];
    $city_name = $_POST['city_name'];  // Now selected from dropdown dynamically

    $pfp = $_FILES['imgupld']['name'];
    $type = strtolower($_FILES['imgupld']['type']);
    $tmp_name = $_FILES['imgupld']['tmp_name'];
    $size = $_FILES['imgupld']['size'];
    $folder = "../upload/" . $pfp;

    // Check for duplicate username in users table
    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $userExists = mysqli_query($conn, $checkUser);

    // Check for duplicate email in doctors table
    $checkEmail = "SELECT * FROM doctors WHERE email = '$Email'";
    $emailExists = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($userExists) > 0 || mysqli_num_rows($emailExists) > 0) {
        echo "<script>alert('Username or Email already exists. Please choose another.');</script>";
        exit;
    }

    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    if (in_array($type, $allowedTypes)) {
        if ($size <= 4000000) {
            $userInsert = "INSERT INTO users(username, password, role) VALUES ('$username', '$password', 'Doctor')";
            $userRun = mysqli_query($conn, $userInsert);

            if ($userRun) {
                $insert = "INSERT INTO doctors(username, full_name, email, phone, specialist, profile_description, city, profile_photo)
                           VALUES ('$username', '$FullName', '$Email', '$Phone', '$Spec', '$Profile', '$city_name', '$pfp')";

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
    <h3 class="mb-4 text-center">Add Doctor</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="text-center mb-4">
        <img src="" class="profile-img" alt="Profile Photo"><br>
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

       <label for="city_name" class="form-label">City</label>
       <select class="form-select" name="city_name" id="city_name" required>
            <option value="" disabled selected>Select your city</option>
            <?php while($city = mysqli_fetch_assoc($cityResult)) { ?>
                <option value="<?php echo $city['city_name']; ?>"><?php echo $city['city_name']; ?></option>
            <?php } ?>
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

      <label class="form-label mt-3">Profile</label>
            <textarea class="form-control" name="Profile" rows="4" required></textarea>

      <button type="submit" name="btn" class="btn btn-primary w-100">Add Doctor</button>
    </form>
  </div>
  </div>

</body>
</html>
