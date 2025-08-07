<?php 
echo"working"
session_start();
include("db.php");

// Handle login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if ($user['password'] === $password) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      if ($user['role'] === 'Admin') {
        $_SESSION['admin'] = $user['username'];
        header("Location: admin/admin.php");
        exit;
      } elseif ($user['role'] === 'Patient') {
        $_SESSION['patient'] = $user['username'];
        header("Location: index.php");
        exit;
      } elseif ($user['role'] === 'Doctor') {
        $_SESSION['doctor'] = $user['username'];
        header("Location: index.php");
        exit;
      } else {
        $_SESSION['error'] = "Unknown user role.";
      }
    } else {
      $_SESSION['error'] = "Incorrect password.";
    }
  } else {
    $_SESSION['error'] = "User not found.";
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

  <?php if (isset($_SESSION['error'])): ?>
    <div style="color:red; padding:10px; text-align:center;">
      <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <!-- Username Field -->
    <div class="form-group">
      <label for="username" class="form-label">Username</label>
      <div class="input-wrapper">
        <span class="input-icon">👤</span>
        <input type="text" id="username" name="username" placeholder="Enter your username" required />
      </div>
    </div>

    <!-- Password Field -->
    <div class="form-group">
      <label for="password" class="form-label">Password</label>
      <div class="input-wrapper">
        <span class="input-icon">🔒</span>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />
        <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
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
    <button type="submit" class="submit-btn">LogIn</button>

    <!-- Footer -->
    <div class="footer-text">
      Don't have an account? <a href="./register.php">Register</a>
    </div>
  </form>
</div>

<script>
function togglePassword() {
  const passwordInput = document.getElementById("password");
  const toggleBtn = document.querySelector(".toggle-password");
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleBtn.textContent = "🙈";
  } else {
    passwordInput.type = "password";
    toggleBtn.textContent = "👁️";
  }
}
</script>

</body>
</html>
