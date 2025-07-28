<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Page</title>
  <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background: linear-gradient(to bottom right, #f9fafb, #f1f5f9, #e0f2f1);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .card {
      width: 100%;
      max-width: 480px;
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      backdrop-filter: blur(6px);
      padding: 40px;
    }

    .card-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .card-title {
      font-size: 28px;
      font-weight: bold;
      color: #1f2937;
      margin-bottom: 8px;
    }

    .card-description {
      font-size: 14px;
      color: #6b7280;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-label {
      font-size: 14px;
      font-weight: 500;
      color: #1f2937;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper input {
      width: 100%;
      padding: 12px 40px 12px 38px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 15px;
      transition: all 0.2s ease;
    }

    .input-wrapper input:focus {
      border-color: #0d9488;
      outline: none;
      box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2);
    }

    .input-icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: #9ca3af;
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: #9ca3af;
      background: none;
      border: none;
      cursor: pointer;
    }

    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
    }

    .remember {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #374151;
    }

    .remember input {
      width: 16px;
      height: 16px;
    }

    .forgot-password {
      color: #0d9488;
      text-decoration: none;
      font-weight: 500;
    }

    .submit-btn {
      background-color: #0d9488;
      color: white;
      font-weight: 600;
      font-size: 16px;
      padding: 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .submit-btn:hover {
      background-color: #0f766e;
      transform: scale(1.02);
    }

    .submit-btn:active {
      transform: scale(0.98);
    }

    .footer-text {
      margin-top: 24px;
      text-align: center;
      font-size: 14px;
      color: #6b7280;
    }

    .footer-text a {
      color: #0d9488;
      text-decoration: none;
      font-weight: 500;
    }
  </style>
</head>
<body>

    <header class="header">

    <a href="./index.php" class="logo"> <i class="fas fa-heartbeat"></i> <strong>CARE</strong>medical </a>

    <nav class="navbar">
        <a href="./login.php" class="log-in-button">Login</a>
        <a href="./register.php" id="Register">Register</a>
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
