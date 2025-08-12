<?php
include("./db.php");

// If the form is submitted
if (isset($_POST['create'])) { // Matches button name in HTML
    // Get form data
    $FullName = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $Email = $_POST['email'];
    $Phone = $_POST['phone'];
    $city_name = $_POST['city'];

    // Check if username already exists
    $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    // Check if email already exists in patients
    $checkEmail = mysqli_query($conn, "SELECT * FROM patients WHERE email = '$Email'");

    if (mysqli_num_rows($checkUser) > 0 || mysqli_num_rows($checkEmail) > 0) {
        echo "<script>alert('Username or Email already exists. Please choose another.');</script>";
    } else {
        // Insert into users table
        $userInsert = "INSERT INTO users(username, password, role) 
                       VALUES ('$username', '$password', 'Patient')";
        $userRun = mysqli_query($conn, $userInsert);

        if ($userRun) {
            // Insert into patients table (no profile_photo column now)
            $insert = "INSERT INTO patients(username, full_name, email, phone, city)
                       VALUES ('$username', '$FullName', '$Email', '$Phone', '$city_name')";
            $run = mysqli_query($conn, $insert);

            if ($run) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Error adding patient: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger mt-3'>Error creating user account: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/register.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <a href="./index.php" class="logo"><i class="fas fa-heartbeat"></i> <strong>CARE</strong>medical</a>
    <nav class="navbar">
        <a href="./login.php" class="log-in-button">Login</a>
    </nav>
</header>

<div class="card" style="margin-top:10vh;">
  <div class="card-header">
    <div class="card-title">Create Account</div>
    <div class="card-description">Fill in your details to sign up</div>
  </div>

  <!-- Form -->
  <form action="" method="POST">

    <!-- Full Name -->
    <div class="form-group">
      <label class="form-label">Full Name</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fas fa-user"></i></span>
        <input type="text" name="full_name" placeholder="Enter your full name" required />
      </div>
    </div>

    <!-- Username -->
    <div class="form-group">
      <label class="form-label">Username</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fas fa-user-tag"></i></span>
        <input type="text" name="username" placeholder="Choose a username" required />
      </div>
    </div>

    <!-- Email -->
    <div class="form-group">
      <label class="form-label">Email</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fas fa-envelope"></i></span>
        <input type="email" name="email" placeholder="Enter your email" required />
      </div>
    </div>

    <!-- Phone -->
    <div class="form-group">
      <label class="form-label">Phone</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fas fa-phone-alt"></i></span>
        <input type="tel" name="phone" placeholder="Enter your phone number" required />
      </div>
    </div>

    <!-- City -->
    <div class="form-group">
      <label class="form-label">City</label>
      <select name="city" required>
        <option value="" disabled selected>Select your city</option>
        <option value="Abbottabad">Abbottabad</option>
        <option value="Bahawalpur">Bahawalpur</option>
        <option value="Karachi">Karachi</option>
        <option value="Lahore">Lahore</option>
        <option value="Multan">Multan</option>
      </select>
    </div>

    <!-- Password -->
    <div class="form-group">
      <label class="form-label">Password</label>
      <div class="input-wrapper">
        <span class="input-icon"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" placeholder="Enter your password" required />
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" name="create" class="submit-btn">Register</button>

    <div class="footer-text">
      Already have an account? <a href="./login.php">Login</a>
    </div>
  </form>
</div>

</body>
</html>
