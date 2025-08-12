<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hospital");
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$doctor_username = $_SESSION['username'];
$message = "";

// Save availability
if (isset($_POST['save_availability'])) {
    $days_selected = isset($_POST['days_of_week']) ? $_POST['days_of_week'] : [];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    if (empty($days_selected) || empty($start_time) || empty($end_time)) {
        $message = "⚠️ Please select at least one day and fill out the time fields!";
    }
    elseif ($start_time >= $end_time) {
        $message = "⚠️ Start time must be earlier than end time!";
    }
    else {
        $success = [];
        $duplicates = [];
        $errors = [];

        foreach ($days_selected as $day) {
            $check_sql = "SELECT * FROM doctor_availability
                          WHERE doctor_username='$doctor_username'
                          AND day_of_week='$day'
                          AND start_time='$start_time'
                          AND end_time='$end_time'";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                $duplicates[] = $day;
            }
            else {
                $insert_sql = "INSERT INTO doctor_availability (doctor_username, day_of_week, start_time, end_time)
                               VALUES ('$doctor_username', '$day', '$start_time', '$end_time')";

                if (mysqli_query($conn, $insert_sql)) {
                    $success[] = $day;
                } else {
                    $errors[] = $day;
                }
            }
        }

        if (!empty($success)) {
            $message .= "✅ Added for: " . implode(", ", $success) . "<br>";
        }
        if (!empty($duplicates)) {
            $message .= "⚠️ Already exists: " . implode(", ", $duplicates) . "<br>";
        }
        if (!empty($errors)) {
            $message .= "❌ Error for: " . implode(", ", $errors);
        }
    }
}

// Delete availability
if (isset($_POST['delete_availability'])) {
    $id_to_delete = $_POST['availability_id'];
    $delete_sql = "DELETE FROM doctor_availability
                   WHERE id='$id_to_delete' AND doctor_username='$doctor_username'";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "✅ Availability deleted!";
    } else {
        $message = "❌ Could not delete: " . mysqli_error($conn);
    }
}

// Get availability list
$sql = "SELECT * FROM doctor_availability
        WHERE doctor_username='$doctor_username'
        ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
        start_time";
$result = mysqli_query($conn, $sql);

$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

function to12Hour($time) {
    return date('g:i A', strtotime($time));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Weekly Schedule</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Your existing CSS styles here (unchanged) */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            line-height: 2;
            font-size: 1.65rem;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.08);
        }
        h1 {
            color: #16a085;
            text-align: center;
            margin-bottom: 40px;
            font-size: 4.2rem;
            text-transform: uppercase;
        }
        h2 {
            color: #16a085;
            border-bottom: 4px solid #16a085;
            padding-bottom: 10px;
            font-size: 3rem;
            margin-bottom: 20px;
        }
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
            font-size: 1.8rem;
        }
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
            transform: scale(1.8);
            cursor: pointer;
        }
        .day-label {
            cursor: pointer;
            padding: 10px 14px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 1.65rem;
        }
        .day-checkbox input[type="checkbox"]:checked + .day-label {
            background-color: #16a085;
            color: white;
            font-weight: bold;
        }
        .save-button {
            background-color: #16a085;
            color: white;
            padding: 20px 40px;
            border: none;
            border-radius: 10px;
            font-size: 2rem;
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
            padding: 14px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.65rem;
            transition: background 0.3s ease;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
        .message {
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center;
            background-color: #e8f8f5;
            border: 1px solid #16a085;
            color: #138d75;
            font-size: 1.65rem;
        }
        .availability-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 1.65rem;
        }
        .availability-table th,
        .availability-table td {
            padding: 28px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .availability-table th {
            background-color: #16a085;
            color: white;
            font-weight: bold;
            font-size: 1.8rem;
        }
        .empty-state {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 35px;
            font-size: 1.65rem;
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
        <a href="./logout.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </nav>
</header>

<div class="container">
    <h1>🕒 Set Your Weekly Schedule</h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="form-section">
        <h2>➕ Add New Availability</h2>
        <form method="POST">
            <div class="form-group">
                <label>📅 Select Days of the Week:</label>
                <div class="days-container">
                    <?php foreach ($days_of_week as $day): ?>
                        <div class="day-checkbox">
                            <input type="checkbox" name="days_of_week[]" value="<?php echo $day; ?>" id="day_<?php echo strtolower($day); ?>">
                            <label for="day_<?php echo strtolower($day); ?>" class="day-label"><?php echo $day; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="start_time">🕐 Start Time:</label>
                <input type="time" name="start_time" id="start_time" required>
            </div>
            <div class="form-group">
                <label for="end_time">🕐 End Time:</label>
                <input type="time" name="end_time" id="end_time" required>
            </div>
            <button type="submit" name="save_availability" class="save-button">💾 Save Time Slots for Selected Days</button>
        </form>
    </div>

    <h2>📋 Your Current Weekly Schedule</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
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
                <?php while ($availability_row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $availability_row['day_of_week']; ?></td>
                        <td><?php echo to12Hour($availability_row['start_time']); ?></td>
                        <td><?php echo to12Hour($availability_row['end_time']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="availability_id" value="<?php echo $availability_row['id']; ?>">
                                <button type="submit" name="delete_availability" class="delete-button" onclick="return confirm('Are you sure you want to delete this time slot? This cannot be undone!')">🗑️ Delete</button>
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

    <a href="./index.php" class="back-link">← Back to Dashboard</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const dayCheckboxes = document.querySelectorAll('input[name="days_of_week[]"]');

    startTimeInput.addEventListener('change', function() {
        if (startTimeInput.value && endTimeInput.value && startTimeInput.value >= endTimeInput.value) {
            alert('⚠️ Start time must be before end time!');
            startTimeInput.focus();
        }
    });

    endTimeInput.addEventListener('change', function() {
        if (startTimeInput.value && endTimeInput.value && endTimeInput.value <= startTimeInput.value) {
            alert('⚠️ End time must be after start time!');
            endTimeInput.focus();
        }
    });

    const daysContainer = document.querySelector('.days-container');
    const helperButtons = document.createElement('div');
    helperButtons.style.textAlign = 'center';
    helperButtons.style.marginBottom = '15px';
    helperButtons.innerHTML = `
        <button type="button" id="selectAll">✓ Select All Days</button>
        <button type="button" id="clearAll">✗ Clear All Days</button>
    `;
    daysContainer.parentNode.insertBefore(helperButtons, daysContainer);

    document.getElementById('selectAll').addEventListener('click', function() {
        dayCheckboxes.forEach(checkbox => checkbox.checked = true);
    });
    document.getElementById('clearAll').addEventListener('click', function() {
        dayCheckboxes.forEach(checkbox => checkbox.checked = false);
    });
});
</script>
</body>
</html>
