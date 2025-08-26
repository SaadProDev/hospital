<?php
session_start();
include("../db.php");

// ✅ Check login & role
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$current_username = trim($_SESSION['username']);
$success_message = "";
$error_message = "";

// ✅ Handle form submission
if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $specialist = mysqli_real_escape_string($conn, $_POST['specialist']);
    $profile_description = mysqli_real_escape_string($conn, $_POST['profile_description']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($full_name) || empty($email) || empty($phone) || empty($specialist) || empty($city)) {
        $error_message = "Please fill in all required fields!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address!";
    } else {
        // Handle file upload
        $profile_photo = "";
        if (!empty($_FILES['profile_photo']['name'])) {
            $target_dir = "../upload/";
            $profile_photo = basename($_FILES["profile_photo"]["name"]);
            $target_file = $target_dir . $profile_photo;

            $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
            if ($check === false) {
                $error_message = "File is not an image!";
            } elseif (!move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                $error_message = "Error uploading file!";
            }
        }

        // ✅ Update DB if no error
        if (empty($error_message)) {
            $update_doctor = "UPDATE doctors SET 
                full_name = '$full_name',
                email = '$email',
                phone = '$phone',
                specialist = '$specialist',
                profile_description = '$profile_description',
                city = '$city'";

            if (!empty($profile_photo)) {
                $update_doctor .= ", profile_photo = '$profile_photo'";
            }
            $update_doctor .= " WHERE username = '$current_username'";

            if (mysqli_query($conn, $update_doctor)) {
                $update_user = "UPDATE users SET username = '$current_username'";
                if (!empty($new_password)) {
                    $update_user .= ", password = '$new_password'";
                }
                $update_user .= " WHERE username = '$current_username'";

                if (mysqli_query($conn, $update_user)) {
                    $success_message = "Profile updated successfully!";
                } else {
                    $error_message = "Error updating user table: " . mysqli_error($conn);
                }
            } else {
                $error_message = "Error updating doctor table: " . mysqli_error($conn);
            }
        }
    }
}

// ✅ Fetch doctor data
$get_doctor = mysqli_query($conn, "SELECT d.*, u.password FROM doctors d 
    JOIN users u ON d.username = u.username 
    WHERE d.username = '$current_username'");
$doctor = mysqli_fetch_assoc($get_doctor);

// ✅ Fetch cities
$cities_result = mysqli_query($conn, "SELECT city_name FROM cities ORDER BY city_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor Profile - CARE Medical</title>

    <!-- Font Awesome + Theme CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        .appointment .form {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 500px;
            margin: auto;
        }
        .appointment .form .box {
            margin-bottom: 15px;
            width: 100%;
            font-size: 18px;
            padding: 14px 16px;
            border: 2px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        select.box {
            height: 50px;
            background-color: #fff;
        }
        textarea.box {
            min-height: 120px;
            resize: vertical;
        }
        input[type="file"].box {
            padding: 8px;
        }
        .current-photo {
            text-align: center;
            margin-bottom: 15px;
        }
        .current-photo img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ddd;
        }
        .success {
            background:#d4edda;
            color:#155724;
            padding:10px;
            text-align:center;
            margin-bottom:15px;
        }
        .error {
            background:#f8d7da;
            color:#721c24;
            padding:10px;
            text-align:center;
            margin-bottom:15px;
        }
        .form label {
        font-size: 18px;
        font-weight: bold;
        margin: 8px 0 4px;
        display: block;
    }
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <a href="./index.php" class="logo"> 
        <i class="fas fa-stethoscope"></i> 
        <strong>CARE</strong>medical - Doctor 
    </a>

    <nav class="navbar">
        <a href="./index.php">Home</a>
        <a href="./appointment.php">My Appointments</a>
        <a href="./availability.php">Set Availability</a>
        <a href="./docprofile.php">Profile</a>
        <a href="./logout.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</header>

<!-- Edit Profile -->
<section class="appointment" style="margin-top:120px;">
    <h1 class="heading">Edit <span>Profile</span></h1>

    <?php if (!empty($success_message)): ?>
        <div class="success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form">
    <!-- Current Photo -->
    <?php if (!empty($doctor['profile_photo'])): ?>
        <div class="current-photo">
            <img src="../upload/<?php echo $doctor['profile_photo']; ?>" alt="Profile Photo">
        </div>
    <?php endif; ?>

    <!-- Upload New Photo -->
    <label for="profile_photo">Profile Photo</label>
    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="box">

    <!-- Full Name -->
    <label for="full_name">Full Name</label>
    <input type="text" id="full_name" name="full_name" value="<?php echo $doctor['full_name']; ?>" placeholder="Full Name" class="box" required>

    <!-- Email -->
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?php echo $doctor['email']; ?>" placeholder="Email" class="box" required>

    <!-- Phone -->
    <label for="phone">Phone</label>
    <input type="tel" id="phone" name="phone" value="<?php echo $doctor['phone']; ?>" placeholder="Phone" class="box" required>

    <!-- Specialist -->
    <label for="specialist">Specialist</label>
    <select id="specialist" class="form-select box" name="specialist" required>
        <option value="" disabled>Select a specialty</option>
        <option value="Dermatology" <?php echo ($doctor['specialist'] == 'Dermatology') ? 'selected' : ''; ?>>Dermatology</option>
        <option value="Oncologist" <?php echo ($doctor['specialist'] == 'Oncologist') ? 'selected' : ''; ?>>Oncologist</option>
        <option value="Cardiologist" <?php echo ($doctor['specialist'] == 'Cardiologist') ? 'selected' : ''; ?>>Cardiologist</option>
        <option value="Gastroenterology" <?php echo ($doctor['specialist'] == 'Gastroenterology') ? 'selected' : ''; ?>>Gastroenterology</option>
        <option value="Neurologist" <?php echo ($doctor['specialist'] == 'Neurologist') ? 'selected' : ''; ?>>Neurologist</option>
        <option value="Anesthesiology" <?php echo ($doctor['specialist'] == 'Anesthesiology') ? 'selected' : ''; ?>>Anesthesiology</option>
        <option value="Psychiatry" <?php echo ($doctor['specialist'] == 'Psychiatry') ? 'selected' : ''; ?>>Psychiatry</option>
        <option value="Family medicine" <?php echo ($doctor['specialist'] == 'Family medicine') ? 'selected' : ''; ?>>Family medicine</option>
        <option value="Ophthalmologist" <?php echo ($doctor['specialist'] == 'Ophthalmologist') ? 'selected' : ''; ?>>Ophthalmologist</option>
        <option value="Pediatrics" <?php echo ($doctor['specialist'] == 'Pediatrics') ? 'selected' : ''; ?>>Pediatrics</option>
    </select>

    <!-- City -->
    <label for="city">City</label>
    <select id="city" name="city" class="box" required>
        <option value="">Select City</option>
        <?php while ($city = mysqli_fetch_assoc($cities_result)): ?>
            <option value="<?php echo $city['city_name']; ?>" <?php echo ($city['city_name'] == $doctor['city']) ? 'selected' : ''; ?>>
                <?php echo $city['city_name']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- Password -->
    <label for="password">New Password</label>
    <input type="password" id="password" name="password" placeholder="Leave blank to keep current" class="box">

    <!-- Description -->
    <label for="profile_description">Profile Description</label>
    <textarea id="profile_description" name="profile_description" placeholder="Profile Description" class="box"><?php echo $doctor['profile_description']; ?></textarea>

    <!-- Submit -->
    <input type="submit" name="update_profile" value="Update Profile" class="btn">
</form>
</section>

<!-- Footer -->
<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>quick links</h3>
            <a href="./doctor_dashboard.php">home</a>
            <a href="./appointment.php">appointments</a>
            <a href="./availability.php">availability</a>
            <a href="./docprofile.php">profile</a>
        </div>
        <div class="box">
            <h3>support</h3>
            <a href="#">help center</a>
            <a href="#">contact admin</a>
        </div>
        <div class="box">
            <h3>contact info</h3>
            <a href="#">+8801688238801</a>
            <a href="#">support@caremedical.com</a>
            <a href="#">sylhet, bangladesh</a>
        </div>
    </div>
    <div class="credit">created by <span>Data Drifters</span></div>
</section>

<script src="../js/script.js"></script>
</body>
</html>
