<?php

session_start();

$database_connection = mysqli_connect("localhost", "root", "", "hospital");


if (!$database_connection) {
    die("❌ Could not connect to database: " . mysqli_connect_error());
}


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    
    header("Location: ../login.php");
    exit();
}


$logged_in_doctor = $_SESSION['username'];


$user_message = "";

if (isset($_POST['save_availability'])) {
    

    $selected_days = isset($_POST['days_of_week']) ? $_POST['days_of_week'] : []; 
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    

    if (empty($selected_days) || empty($start_time) || empty($end_time)) {
        $user_message = "⚠️ Please select at least one day and fill out the time fields!";
    }
    
    else if ($start_time >= $end_time) {
        $user_message = "⚠️ Start time must be earlier than end time!";
    }
    else {
        
        $successful_days = [];
        $duplicate_days = [];
        $error_days = [];

        foreach ($selected_days as $selected_day) {
            

            $check_query = "SELECT * FROM doctor_availability 
                           WHERE doctor_username = '$logged_in_doctor' 
                           AND day_of_week = '$selected_day' 
                           AND start_time = '$start_time' 
                           AND end_time = '$end_time'";
            
            $check_result = mysqli_query($database_connection, $check_query);
            
            
            if (mysqli_num_rows($check_result) > 0) {
                $duplicate_days[] = $selected_day;
            }
            else {
                
                $save_query = "INSERT INTO doctor_availability (doctor_username, day_of_week, start_time, end_time) 
                              VALUES ('$logged_in_doctor', '$selected_day', '$start_time', '$end_time')";
                
                if (mysqli_query($database_connection, $save_query)) {
                    $successful_days[] = $selected_day;
                } else {
                    $error_days[] = $selected_day;
                }
            }
        }
        

        $message_parts = [];
        
        if (!empty($successful_days)) {
            $message_parts[] = "✅ Successfully added availability for: " . implode(', ', $successful_days);
        }
        
        if (!empty($duplicate_days)) {
            $message_parts[] = "⚠️ Already exists for: " . implode(', ', $duplicate_days);
        }
        
        if (!empty($error_days)) {
            $message_parts[] = "❌ Error saving for: " . implode(', ', $error_days);
        }
        
        $user_message = implode('<br>', $message_parts);
    }
}

if (isset($_POST['delete_availability'])) {
    $availability_id = $_POST['availability_id'];
    
    $delete_query = "DELETE FROM doctor_availability 
                    WHERE id = '$availability_id' 
                    AND doctor_username = '$logged_in_doctor'";
    
    if (mysqli_query($database_connection, $delete_query)) {
        $user_message = "✅ Availability deleted successfully!";
    } else {
        $user_message = "❌ Error deleting availability: " . mysqli_error($database_connection);
    }
}


$get_availability_query = "SELECT * FROM doctor_availability 
                          WHERE doctor_username = '$logged_in_doctor' 
                          ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
                          start_time";

$availability_results = mysqli_query($database_connection, $get_availability_query);


$days_of_week = [
    'Monday',
    'Tuesday', 
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday'
];

function convert_to_12_hour_format($time_24_hour) {
    return date('g:i A', strtotime($time_24_hour));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Weekly Schedule</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- CSS Styles to make it look nice -->
    <style>
     /* ===============================
   Page Base Styling
   =============================== */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 20px;
    line-height: 2; /* increased line spacing */
    font-size: 1.65rem; /* was 1.1rem */
}

/* ===============================
   Main Container
   =============================== */
.container {
    max-width: 1000px;
    margin: 0 auto;
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.08);
}

/* ===============================
   Page Title
   =============================== */
h1 {
    color: #16a085;
    text-align: center;
    margin-bottom: 40px;
    font-size: 4.2rem; /* was 2.8rem */
    text-transform: uppercase;
}

h2 {
    color: #16a085;
    border-bottom: 4px solid #16a085;
    padding-bottom: 10px;
    font-size: 3rem; /* was 2rem */
    margin-bottom: 20px;
}

/* ===============================
   Form Section
   =============================== */
.form-section {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 40px;
    border-left: 6px solid #16a085;
}

.form-group {
    margin-bottom: 25px;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #2c3e50;
    font-size: 1.8rem; /* was 1.2rem */
}

/* ===============================
   Days of the Week Selection
   =============================== */
.days-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 18px;
    margin: 12px 0;
    padding: 18px;
    background: white;
    border: 2px solid #e8e8e8;
    border-radius: 10px;
}

.day-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
}

.day-checkbox input[type="checkbox"] {
    transform: scale(1.8); /* was 1.4 */
    cursor: pointer;
}

.day-label {
    cursor: pointer;
    padding: 10px 14px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 1.65rem; /* was 1.1rem */
}

.day-checkbox input[type="checkbox"]:checked + .day-label {
    background-color: #16a085;
    color: white;
    font-weight: bold;
}

.day-label:hover {
    background-color: #ecf0f1;
}

/* ===============================
   Buttons
   =============================== */
.save-button {
    background-color: #16a085;
    color: white;
    padding: 20px 40px; /* larger padding */
    border: none;
    border-radius: 10px;
    font-size: 2rem; /* was 1.3rem */
    cursor: pointer;
    width: 100%;
    font-weight: bold;
    transition: background 0.3s ease;
}

.save-button:hover {
    background-color: #138d75;
}

.delete-button {
    background-color: #e74c3c;
    color: white;
    padding: 14px 24px; /* larger */
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.65rem; /* was 1.1rem */
    transition: background 0.3s ease;
}

.delete-button:hover {
    background-color: #c0392b;
}

/* Helper buttons */
#selectAll, #clearAll {
    border: none;
    padding: 14px 20px; /* larger */
    border-radius: 6px;
    margin: 0 5px;
    cursor: pointer;
    font-weight: bold;
    font-size: 1.65rem; /* was 1rem */
}

#selectAll {
    background: #16a085;
    color: white;
}

#selectAll:hover {
    background: #138d75;
}

#clearAll {
    background: #e74c3c;
    color: white;
}

#clearAll:hover {
    background: #c0392b;
}

/* ===============================
   Messages
   =============================== */
.message {
    padding: 20px;
    border-radius: 6px;
    margin-bottom: 25px;
    font-weight: bold;
    text-align: center;
    background-color: #e8f8f5;
    border: 1px solid #16a085;
    color: #138d75;
    font-size: 1.65rem; /* was 1.1rem */
}

/* ===============================
   Availability Table
   =============================== */
.availability-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    font-size: 1.65rem; /* was 1.1rem */
}

.availability-table th,
.availability-table td {
    padding: 28px; /* was 20px */
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.availability-table th {
    background-color: #16a085;
    color: white;
    font-weight: bold;
    font-size: 1.8rem; /* was 1.2rem */
}

.availability-table tr:hover {
    background-color: #f9f9f9;
}

/* ===============================
   Empty State
   =============================== */
.empty-state {
    text-align: center;
    color: #7f8c8d;
    font-style: italic;
    padding: 35px;
    font-size: 1.65rem; /* was 1.1rem */
}

/* ===============================
   Back Link
   =============================== */
.back-link {
    display: inline-block;
    margin-top: 25px;
    padding: 16px 32px; /* larger */
    background-color: #95a5a6;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 1.65rem; /* was 1.1rem */
}

.back-link:hover {
    background-color: #7f8c8d;
}

h1{
    margin-top: 5vh;
}
    </style>
</head>
<body>
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
        <a href="./logout.php" class="btn btn-danger" 
   onclick="return confirm('Are you sure you want to logout?')">
   Logout
</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>

</header>
    <div class="container">
        <!-- Page Title -->
        <h1>🕒 Set Your Weekly Schedule</h1>
        
        
        <!-- Show any messages to the user -->
        <?php if (!empty($user_message)): ?>
            <div class="message">
                <?php echo $user_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Form for adding new availability -->
        <div class="form-section">
            <h2>➕ Add New Availability</h2>
            
            <form method="POST">
                <!-- Days selection with checkboxes -->
                <div class="form-group">
                    <label>📅 Select Days of the Week:</label>
                    <div class="days-container">
                        <?php foreach ($days_of_week as $day): ?>
                            <div class="day-checkbox">
                                <input type="checkbox" 
                                       name="days_of_week[]" 
                                       value="<?php echo $day; ?>" 
                                       id="day_<?php echo strtolower($day); ?>">
                                <label for="day_<?php echo strtolower($day); ?>" class="day-label">
                                    <?php echo $day; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <small style="color: #7f8c8d;">Check all the days you want to work these hours</small>
                </div>
                
                <!-- Start time -->
                <div class="form-group">
                    <label for="start_time">🕐 Start Time:</label>
                    <input type="time" name="start_time" id="start_time" required>
                    <small style="color: #7f8c8d;">When do you start seeing patients?</small>
                </div>
                
                <!-- End time -->
                <div class="form-group">
                    <label for="end_time">🕐 End Time:</label>
                    <input type="time" name="end_time" id="end_time" required>
                    <small style="color: #7f8c8d;">When do you finish seeing patients?</small>
                </div>
                
                <!-- Submit button -->
                <button type="submit" name="save_availability" class="save-button">
                    💾 Save Time Slots for Selected Days
                </button>
            </form>
        </div>
        
        <!-- Table showing current availability -->
        <h2>📋 Your Current Weekly Schedule</h2>
        
        <?php if (mysqli_num_rows($availability_results) > 0): ?>
            <table class="availability-table">
                <thead>
                    <tr>
                        <th>📅 Day</th>
                        <th>🕐 Start Time</th>
                        <th>🕐 End Time</th>
                        <th>⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($availability_row = mysqli_fetch_assoc($availability_results)): ?>
                        <tr>
                            <td><?php echo $availability_row['day_of_week']; ?></td>
                            <td><?php echo convert_to_12_hour_format($availability_row['start_time']); ?></td>
                            <td><?php echo convert_to_12_hour_format($availability_row['end_time']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="availability_id" value="<?php echo $availability_row['id']; ?>">
                                    <button type="submit" 
                                            name="delete_availability" 
                                            class="delete-button"
                                            onclick="return confirm('Are you sure you want to delete this time slot? This cannot be undone!')">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>📭 No schedule set yet</h3>
                <p>You haven't added any availability yet. Use the form above to set your working hours!</p>
            </div>
        <?php endif; ?>
        
        <!-- Back to dashboard link -->
        <a href="./index.php" class="back-link">← Back to Dashboard</a>
    </div>

    <script src="./availabilty.js"></script>
</body>
</html>