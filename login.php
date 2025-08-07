<?php 
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
    <span class="input-icon">
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 448 512">
        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zM313.6 288h-16.7c-22.2 10.3-46.9 16-72.9 16s-50.7-5.7-72.9-16h-16.7C85.96 288 0 373.1 0 480c0 17.7 14.33 32 32 32h384c17.7 0 32-14.3 32-32C448 373.1 362 288 313.6 288z"/>
      </svg>
    </span>
    <input type="text" id="username" name="username" placeholder="Enter your username" required />
  </div>
</div>


    <!-- Password Field -->
   <div class="form-group">
  <label for="password" class="form-label">Password</label>
  <div class="input-wrapper">
    <span class="input-icon">
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 448 512">
        <path d="M400 192h-24V128C376 57.31 318.7 0 248 0S120 57.31 120 128v64H96c-17.67 0-32 14.33-32 32v256c0 17.7 14.33 32 32 32h304c17.7 0 32-14.3 32-32V224C432 206.3 417.7 192 400 192zM160 128c0-48.6 39.4-88 88-88s88 39.4 88 88v64H160V128z"/>
      </svg>
    </span>
    <input type="password" id="password" name="password" placeholder="Enter your password" required />
    <button type="button" class="toggle-password" onclick="togglePassword(this)">
      <svg class="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 576 512">
        <path d="M572.52 241.4C518.6 135.5 407.1 64 288 64S57.38 135.5 3.48 241.4a48.1 48.1 0 000 29.2C57.38 376.5 168.9 448 288 448s230.6-71.5 284.5-177.4a48.1 48.1 0 000-29.2zM288 400c-97.05 0-189.6-57.2-240.6-144C98.43 169.2 190.1 112 288 112s189.6 57.2 240.6 144C477.6 342.8 385.1 400 288 400zm0-272c-70.58 0-128 57.42-128 128s57.42 128 128 128 128-57.42 128-128S358.6 128 288 128z"/>
      </svg>
    </button>
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
  const eyeSVG = `
    <svg class="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 576 512">
      <path d="M572.52 241.4C518.6 135.5 407.1 64 288 64S57.38 135.5 3.48 241.4a48.1 48.1 0 000 29.2C57.38 376.5 168.9 448 288 448s230.6-71.5 284.5-177.4a48.1 48.1 0 000-29.2zM288 400c-97.05 0-189.6-57.2-240.6-144C98.43 169.2 190.1 112 288 112s189.6 57.2 240.6 144C477.6 342.8 385.1 400 288 400zm0-272c-70.58 0-128 57.42-128 128s57.42 128 128 128 128-57.42 128-128S358.6 128 288 128z"/>
    </svg>
  `;

  const eyeSlashSVG = `
    <svg class="eye-icon" width="16" height="16" fill="currentColor" viewBox="0 0 640 512">
      <path d="M634 471L51.89 5.1c-6.844-5.5-17-4.313-22.48 2.531S24.1 24.98 31.89 30.48L91.04 77.93C34.95 128.8 3.484 199.8 .4883 207.3c-1.656 4.156-1.656 8.719 0 12.88C55.38 334.5 166.9 416 288 416c49.02 0 96.11-13.53 137.5-38.26l90.17 72.57C519.2 455.4 523.6 456.9 528 456.9c4.812 0 9.562-2.094 12.89-6.125C639.1 488.1 640.8 477.8 634 471zM288 368c-88.22 0-170.9-53.39-220.1-136C93.57 221.5 113.8 188.6 143.3 160.4l60.89 49.04c-4.709 11.26-7.293 23.55-7.293 36.56c0 61.76 50.24 112 112 112c13.01 0 25.3-2.584 36.56-7.293L401.6 368H288zM288 224c-8.844 0-16 7.156-16 16c0 3.104 .9385 5.969 2.531 8.406l51.06 41.02C331.1 287.1 336 277.6 336 272C336 248.1 319 224 288 224zM636.5 220.2C581.6 117.8 470.1 48 352 48c-37.47 0-74.12 7.803-108.4 22.42l42.1 33.92C304.8 98.53 320.7 96 336 96c97.05 0 189.6 57.2 240.6 144C567.6 281.4 547.4 314.3 517.9 342.5l53.53 43.11C617.6 344.4 648 273.3 651 265.8C652.6 261.6 652.6 257 651 252.8C648 248.7 636.5 220.2 636.5 220.2z"/>
    </svg>
  `;

  function togglePassword(btn) {
    const input = document.getElementById("password");
    if (input.type === "password") {
      input.type = "text";
      btn.innerHTML = eyeSlashSVG;
    } else {
      input.type = "password";
      btn.innerHTML = eyeSVG;
    }
  }
</script>


</body>
</html>
