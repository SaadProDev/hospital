<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register Page</title>
  <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/style.css">
  
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
      <div class="card-title">Create Account</div>
      <div class="card-description">Fill in your details to sign up</div>
    </div>
    <form onsubmit="handleRegister(event)">
      <!-- Name Field -->
      <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-wrapper">
          <span class="input-icon">👤</span>
          <input type="text" id="name" placeholder="Enter your full name" required />
        </div>
      </div>

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
          <button type="button" class="toggle-password" onclick="togglePassword('password', this)">👁️</button>
        </div>
      </div>

      <!-- Confirm Password Field -->
      <div class="form-group">
        <label for="confirm-password" class="form-label">Confirm Password</label>
        <div class="input-wrapper">
          <span class="input-icon">🔒</span>
          <input type="password" id="confirm-password" placeholder="Re-enter your password" required />
          <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', this)">👁️</button>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="submit-btn">Register</button>

      <!-- Footer -->
      <div class="footer-text">
        Already have an account? <a href="./login.php">Login</a>
      </div>
    </form>
  </div>

  <script>
    function togglePassword(fieldId, btn) {
      const input = document.getElementById(fieldId);
      if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈";
      } else {
        input.type = "password";
        btn.textContent = "👁️";
      }
    }

    function handleRegister(e) {
      e.preventDefault();
      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm-password").value;

      if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return;
      }

      console.log("Register info:", { name, email, password });
      alert("Registration submitted! Check console.");
    }
  </script>
</body>
</html>
