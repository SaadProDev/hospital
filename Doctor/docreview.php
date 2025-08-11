<?php
include("db.php");
if (isset($_GET['un'])) {
    $username = $_GET['un'];

    $select = "SELECT * FROM doctors WHERE username = '$username'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) == 1) {
        $doctor = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Doctor not found!'); window.location.href='ReadDoctor.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No Doctor ID Provided!'); window.location.href='ReadDoctor.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doctor Profile</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f8f8f8;
    }
    .header {
      background: #33d9b2;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }
    .header h1 {
      margin: 0;
      font-size: 1.5rem;
    }
    .profile-container {
      max-width: 1100px;
      margin: 30px auto;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .profile-header {
      display: flex;
      padding: 20px;
      align-items: center;
      border-bottom: 1px solid #ddd;
    }
    .profile-header img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 20px;
      border: 4px solid #33d9b2;
    }
    .profile-header h2 {
      margin: 0;
      font-size: 1.8rem;
      color: #444;
    }
    .profile-header p {
      margin: 5px 0;
      color: #666;
    }
    .about-section {
      padding: 20px;
    }
    .about-section h3 {
      margin-bottom: 10px;
      color: #33d9b2;
    }
    .reviews {
      padding: 20px;
      background: #f8f8f8;
    }
    .review {
      background: white;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .review .stars {
      color: #f1c40f;
    }
    .review strong {
      display: block;
      color: #444;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>



  <div class="profile-container">
    <div class="profile-header">
      <img src="doctor.jpg" alt="Doctor Photo">
      <div>
        <h2><?php echo $doctor['full_name']?></h2>
        <p><?php echo $doctor['specialist']?></p>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo ['city']?></p>
      </div>
    </div>

    <div class="about-section">
      <h3>About the Doctor</h3>
      <p>
        Dr. Sarah Johnson is a board-certified cardiologist with over 10 years of experience. She specializes in preventive heart care, lifestyle medicine, and advanced cardiac treatments.
      </p>
    </div>

    <div class="reviews">
      <h3 style="color:#33d9b2;">Patient Reviews</h3>
      <div class="review">
        <strong>John Smith</strong>
        <div class="stars">★★★★★</div>
        <p>Dr. Johnson is amazing! She took the time to explain everything clearly and made me feel comfortable.</p>
      </div>
      <div class="review">
        <strong>Emily Davis</strong>
        <div class="stars">★★★★☆</div>
        <p>Very knowledgeable and kind. The clinic staff was also very professional.</p>
      </div>
    </div>
  </div>

</body>
</html>
