




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

  <div class="card" style="margin-top:10vh;">
    <div class="card-header">
      <div class="card-title">Create Account</div>
      <div class="card-description">Fill in your details to sign up</div>
    </div>
    <form action="login.php" enctype="multipart/form-data">
  <!-- Name Field -->
  <div class="form-group">
    <label for="name" class="form-label">Full Name</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-user"></i></span>
      <input type="text" id="name" name="full_name" placeholder="Enter your full name" required />
    </div>
  </div>

  <!-- Username Field -->
  <div class="form-group">
    <label for="username" class="form-label">Username</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-user-tag"></i></span>
      <input type="text" id="username" name="username" placeholder="Choose a username" required />
    </div>
  </div>

  <!-- Email Field -->
  <div class="form-group">
    <label for="email" class="form-label">Email</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-envelope"></i></span>
      <input type="email" id="email" name="email" placeholder="Enter your email" required />
    </div>
  </div>

  <!-- Phone Field -->
  <div class="form-group">
    <label for="phone" class="form-label">Phone</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-phone-alt"></i></span>
      <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required />
    </div>
  </div>

  <!-- City Dropdown -->
  <div class="form-group">
  <label for="city" class="form-label">City</label>
  <div class="input-wrapper">
    <select id="city" name="city" required>
      <option value="" selected disabled>Select your city</option>
      <option value="Abbottabad">Abbottabad</option>
      <option value="Bahawalpur">Bahawalpur</option>
      <option value="Chiniot">Chiniot</option>
      <option value="Dera Ghazi Khan">Dera Ghazi Khan</option>
      <option value="Faisalabad">Faisalabad</option>
      <option value="Gujranwala">Gujranwala</option>
      <option value="Gujrat">Gujrat</option>
      <option value="Hyderabad">Hyderabad</option>
      <option value="Islamabad">Islamabad</option>
      <option value="Jhelum">Jhelum</option>
      <option value="Karachi">Karachi</option>
      <option value="Lahore">Lahore</option>
      <option value="Larkana">Larkana</option>
      <option value="Mardan">Mardan</option>
      <option value="Mingora">Mingora</option>
      <option value="Mirpur">Mirpur</option>
      <option value="Multan">Multan</option>
      <option value="Okara">Okara</option>
      <option value="Peshawar">Peshawar</option>
      <option value="Quetta">Quetta</option>
      <option value="Rahim Yar Khan">Rahim Yar Khan</option>
      <option value="Rawalpindi">Rawalpindi</option>
      <option value="Sargodha">Sargodha</option>
      <option value="Sheikhupura">Sheikhupura</option>
      <option value="Sialkot">Sialkot</option>
      <option value="Sukkur">Sukkur</option>
    </select>
  </div>
</div>


  <!-- Password Field -->
  <div class="form-group">
    <label for="password" class="form-label">Password</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-lock"></i></span>
      <input type="password" id="password" name="password" placeholder="Enter your password" required />
      <button type="button" class="toggle-password" onclick="togglePassword('password', this)"><i class="fas fa-eye"></i></button>
    </div>
  </div>

  <!-- Confirm Password Field -->
  <div class="form-group">
    <label for="confirm-password" class="form-label">Confirm Password</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-lock"></i></span>
      <input type="password" id="confirm-password" placeholder="Re-enter your password" required />
      <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', this)"><i class="fas fa-eye"></i></button>
    </div>
  </div>

  <!-- Profile Photo Upload -->
  <div class="form-group">
    <label for="photo" class="form-label">Profile Photo</label>
    <div class="input-wrapper">
      <span class="input-icon"><i class="fas fa-camera"></i></span>
      <input type="file" id="photo" name="profile_photo" accept="image/*" required />
    </div>
  </div>

  <!-- Submit Button -->
  <button type="submit" class="submit-btn"><a href="./login.php">register</a></button>

  <!-- Footer -->
  <div class="footer-text">
    Already have an account? <a href="./login.php">Login</a>
  </div>
</form>


  </div>


    <script>
  // Toggle Password Visibility with Font Awesome Icons
  function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }

  // Optional: Handle Register Button (for debugging or future use)
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
