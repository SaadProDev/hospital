<?php
session_start();
include("db.php");

$error_message = ""; // Store errors here

if (isset($_POST['login'])) {
    $username = strtolower(trim($_POST['username']));
    $password = trim($_POST['password']);

    if ($username != "" && $password != "") {
        $query = "SELECT * FROM users WHERE LOWER(username) = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $role = strtolower(trim($user['role']));

            if ($password === $user['password']) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $role;

                if ($role === 'admin') {
                    header("Location: admin/admin.php");
                    exit();
                } elseif ($role === 'patient') {
                    header("Location: patient/index.php");
                    exit();
                } elseif ($role === 'doctor') {
                    header("Location: doctor/index.php");
                    exit();
                } else {
                    $error_message = "Role not recognized.";
                }
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found.";
        }
    } else {
        $error_message = "Please enter both username and password.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page</title>
  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
  <a href="./index.php" class="logo"> <i class="fas fa-heartbeat"></i> <strong>CARE</strong>medical </a>
  <nav class="navbar">
    <a href="./login.php" class="log-in-button">Login</a>
  </nav>
  <div id="menu-btn" class="fas fa-bars"></div>
</header>

<div class="card">
  <div class="card-header">
    <div class="card-title">Welcome Back</div>
    <div class="card-description">Sign in to your account to continue</div>
  </div>

  <?php if (!empty($error_message)): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 10px; 
                border-radius: 5px; margin-bottom: 15px; text-align:center;">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

  <form method="POST">
    <!-- Username Field -->
    <div class="form-group">
      <label for="username" class="form-label">Username</label>
      <div class="input-wrapper">
        <span class="input-icon">
          <i class="fas fa-user"></i>
        </span>
        <input type="text" id="username" name="username" placeholder="Enter your username" required />
      </div>
    </div>

    <!-- Password Field -->
    <div class="form-group">
      <label for="password" class="form-label">Password</label>
      <div class="input-wrapper">
        <span class="input-icon">
          <i class="fas fa-lock"></i>
        </span>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />
      </div>
    </div>

    <!-- Options -->
    <div class="options">
      <label class="remember">
        <input type="checkbox" id="remember" />
        Remember me
      </label>
      <a href="#" class="forgot-password">Forgot password?</a>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="submit-btn" name="login">Log In</button>

    <!-- Footer -->
    <div class="footer-text">
      Don't have an account? <a href="./register.php">Register</a>
    </div>
  </form>
</div>

</body>
</html>
