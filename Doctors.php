<?php



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>complete responsive hospital website create by win coder</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<!-- header section starts  -->

<header class="header">

    <a href="./index.php" class="logo"> <i class="fas fa-heartbeat"></i> <strong>CARE</strong>medical </a>

    <nav class="navbar">
        <a href="./index.php">home</a>
        <a href="./about.php">about</a>
        <a href="./doctors.php">doctors</a>
        <a href="./appointment.php">appointment</a>
        <a href="./news.php">news</a>
        <!-- <a href="admin/admin.php" class="admin-panel">admin panel</a> -->
        <a href="./login.php" class="log-in-button">Login</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>

</header>


<!-- header section ends -->

<!-- doctors section starts  -->

<section class="doctors" id="doctors">

    <h1 class="heading" style="margin-top:100px;"> our <span>doctors</span> </h1>

    <div class="box-container">
        <?php
        include("./db.php");
        $slc = "SELECT * FROM doctors";
        $run = mysqli_query($conn,$slc);

        while($arr = mysqli_fetch_assoc($run)){ ?>

            <div class="box">
            <img src="./upload/<?php echo $arr['profile_photo'] ?>" 
       onerror="this.onerror=null;this.src='upload\download (1).jpeg';"  
       alt="Doctor Image">
            <h3><?php echo $arr['full_name'] ?></h3>
            <span><?php echo $arr['specialist'] ?></span>
            <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
                
            </div>
        </div>
<?php
        }
        
        ?>

        
    </div>

</section>

<!-- doctors section ends -->

<!-- footer section starts  -->

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3>quick links</h3>
            <a href="#home"> <i class="fas fa-chevron-right"></i> home </a>
            <a href="#about"> <i class="fas fa-chevron-right"></i> about </a>
            <a href="#services"> <i class="fas fa-chevron-right"></i> services </a>
            <a href="#doctors"> <i class="fas fa-chevron-right"></i> doctors </a>
            <a href="#appointment"> <i class="fas fa-chevron-right"></i> appointment </a>
            <a href="#review"> <i class="fas fa-chevron-right"></i> review </a>
            <a href="#blogs"> <i class="fas fa-chevron-right"></i> blogs </a>
        </div>

        <div class="box">
            <h3>our services</h3>
            <a href="#"> <i class="fas fa-chevron-right"></i> dental care </a>
            <a href="#"> <i class="fas fa-chevron-right"></i> message therapy </a>
            <a href="#"> <i class="fas fa-chevron-right"></i> cardioloty </a>
            <a href="#"> <i class="fas fa-chevron-right"></i> diagnosis </a>
            <a href="#"> <i class="fas fa-chevron-right"></i> ambulance service </a>
        </div>

        <div class="box">
            <h3>appointment info</h3>
            <a href="#"> <i class="fas fa-phone"></i> +923071338783 </a>
            <a href="#"> <i class="fas fa-phone"></i> +923071338783 </a>
            <a href="#"> <i class="fas fa-envelope"></i> Datadrifters01@gmail.com </a>
            <a href="#"> <i class="fas fa-envelope"></i> driftersdata@gmail.com </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> Karachi, Pakistan </a>
        </div>

        <div class="box">
            <h3>follow us</h3>
            <a href="#"> <i class="fab fa-faceappointment-f"></i> faceappointment </a>
            <a href="#"> <i class="fab fa-twitter"></i> twitter </a>
            <a href="#"> <i class="fab fa-twitter"></i> twitter </a>
            <a href="#"> <i class="fab fa-instagram"></i> instagram </a>
            <a href="#"> <i class="fab fa-linkedin"></i> linkedin </a>
            <a href="#"> <i class="fab fa-pinterest"></i> pinterest </a>
        </div>

    </div>

    <div class="credit"> created by <span>Data Drifters</span> </div>

</section>

<!-- footer section ends -->


<!-- js file link  -->
<script src="js/script.js"></script>

</body>
</html>

