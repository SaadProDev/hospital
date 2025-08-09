<?php
session_start();
include("../db.php");

// ✅ Only patient can access
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$current_username = $_SESSION['username'];

// ✅ Fetch patient data
$selectQuery = "SELECT * FROM patients WHERE username = '$current_username'";
$result = mysqli_query($conn, $selectQuery);

if (mysqli_num_rows($result) == 1) {
    $patient = mysqli_fetch_assoc($result);
} else {
    echo "<script>alert('Patient not found!');window.location.href='../login.php';</script>";
    exit;
}

// ✅ Fetch cities from DB
$cities_result = mysqli_query($conn, "SELECT city_name FROM cities ORDER BY city_name");

// ✅ Handle update form
if (isset($_POST['btn'])) {
    $new_username = $_POST['UserName'];
    $FullName     = $_POST['FullName'];
    $Email        = $_POST['Email'];
    $Phone        = $_POST['Phone'];
    $City         = $_POST['city_name'];
    $Password     = $_POST['Password'];

    // Check for duplicate username (excluding current)
    $checkUser = "SELECT * FROM users WHERE username = '$new_username' AND username != '$current_username'";
    $userExists = mysqli_query($conn, $checkUser);
    if (mysqli_num_rows($userExists) > 0) {
        echo "<script>alert('Username already exists. Choose another.');</script>";
        exit;
    }

    // Handle profile photo
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
        $updatePatient = "UPDATE patients SET 
            username = '$new_username', 
            full_name = '$FullName', 
            email = '$Email', 
            phone = '$Phone', 
            city = '$City' 
            WHERE username = '$current_username'";
    }

    // Update Users table
    if (!empty($Password)) {
        $updateUser = "UPDATE users SET username = '$new_username', password = '$Password' WHERE username = '$current_username'";
    } else {
        $updateUser = "UPDATE users SET username = '$new_username' WHERE username = '$current_username'";
    }

    $runUser    = mysqli_query($conn, $updateUser);
    $runPatient = mysqli_query($conn, $updatePatient);

    if ($runUser && $runPatient) {
        $_SESSION['username'] = $new_username;
        echo "<script>alert('Profile updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error updating profile: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>complete responsive hospital website create by win coder</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .profile-card {
      width: 100%;
      max-width: 500px;
      margin: 50px auto;
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
    .btn-primary {
      background-color: #33d9b2;
      border: none;
    }
    .btn-primary:hover {
      background-color: #28b098;
    }
    .profile-card form {
    display: flex;
    flex-direction: column;
    gap: 15px; /* Space between form elements */
}

.profile-card label {
    font-weight: 500;
    color: #333;
    margin-bottom: 5px;
}

.profile-card .form-control,
.profile-card select {
    height: 45px;
    border-radius: 8px;
    padding: 10px 12px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.profile-card select {
    background-color: #fff;
}

.profile-card .btn-primary {
    background-color: #33d9b2;
    border: none;
    height: 45px;
    font-size: 16px;
    border-radius: 8px;
}

.profile-card .btn-primary:hover {
    background-color: #28b098;
}

.profile-card .text-center {
    margin-bottom: 20px;
}

.profile-card .form-label {
    margin-top: 10px;
}
.profile-card h3 {
    margin-top: 10vh; /* pushes below navbar */
    text-align: center;
    font-weight: bold;
    font-size: 24px;
    color: #33d9b2; /* matches CAREmedical theme */
}
.profile-card .form-label {
    font-size: 16px; /* increase label size */
    font-weight: 600; /* make it a bit bolder */
    color: #333; /* dark grey for readability */
    margin-bottom: 6px; /* spacing below label */
    display: block;
}


  </style>
</head>
<body>
<header class="header">
    <a href="./index.php" class="logo">
        <i class="fas fa-user"></i>
        <strong>CARE</strong>medical - Patient
    </a>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./appointment.php">Book Appointment</a>
        <a href="./appointment_status.php">Appointments Status</a>
        <a href="./docprofile.php">Profile</a>
        <a href="./logout.php" class="btn btn-danger" onclick="return confirm('Logout?')">Logout</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</header>
<div class="profile-card" style="margin-top=:15vh;">
  <h3 class="mb-4 text-center" style="margin-top:"15vh; !important">My Profile</h3>
  <form method="POST" enctype="multipart/form-data">
    <div class="text-center mb-4">
      <img src="../upload/<?php echo $patient['profile_photo']; ?>" 
           onerror="this.onerror=null;this.src='default.jpg';" 
           class="profile-img" alt="Profile Photo"><br>
      <label class="form-label mt-2">Profile Photo</label><br>
      <input type="file" name="imgupld" class="form-control mt-2">
    </div>

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

    <button type="submit" name="btn" class="btn btn-primary w-100 mt-4">Update Profile</button>
  </form>
</div>

</body>
</html>
