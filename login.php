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
  <style>
    
  </style>
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
    <form onsubmit="handleLogin(event)">
      <!-- Email Field -->
      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <div class="input-wrapper">
          <span class="input-icon">📧</span>
          <input type="email" id="email" placeholder="Enter your email" required />
        </div>
      </div>

      <!-- Password Field -->
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <div class="input-wrapper">
          <span class="input-icon">🔒</span>
          <input type="password" id="password" placeholder="Enter your password" required />
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

    function handleLogin(e) {
      e.preventDefault();
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      console.log("Login attempt:", { email, password });
      alert("Login attempted! Check console.");
    }
  </script>
</body>
</html>
