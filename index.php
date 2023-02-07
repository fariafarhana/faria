<?Php
include 'config.php';
include 'func.php';
session_start();
error_reporting(0);


if ($_GET["x"] == '1') {
    if (isset($_SESSION['username'])) {
        session_destroy();
        header("Location: index.php");
    } else {
        header("Location: login.php");
    }
}

    //Check user type 
    if (isset($_SESSION['username'])) {

        $username = $_SESSION['username'];
        
        $sql = "SELECT * FROM users WHERE  username='$username'";
        $result = mysqli_query($con, $sql);
        $role;
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $full_name = $row['full_name'];
            $email = $row['email'];
            $role = $row['role'];
            
        } 
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, intial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Faria's Gallery</title>
</head>

<body>
    <!-------------------Main Content------------------------------>
    <div class="container main">
        <div class="header-banner">
            <nav class="navbar">
                <a href="#" class="nav-logo">Faria's Gallery</a>
                <ul class="nav-menu ">
                <li class="nav-item">
                        <a href="dash.php" class="nav-link">
                           <?php
                           if($role == "admin"){
                               echo "Dashboard";
                           }else{
                               
                           }
                           ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="profile.php" class="nav-link">
                            <?php echo $username ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="upload.php" class="nav-link">Upload New</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?x=1" class="nav-link">
                            <?php
                            if (isset($_SESSION['username'])) {
                                echo "Logout";
                            } else {
                                echo "Login";
                            }

                            ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="about.html" class="about.html">About us</a>
                    </li>
                    <li class="nav-item">
                        <a href="contact.html" class="contact.html">Contact us</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </nav>
        </div>
        <div class="img-box">

            <?php
            //include "config.php";
            $result = mysqli_connect($server, $user, $pass) or die("Could not connect to database.");
            mysqli_select_db($result, $database) or die("Could not select the databse.");
            $image_query = mysqli_query($result, "select img_name,img_path from image_table where status = 'accepted' ");
            while ($rows = mysqli_fetch_array($image_query)) {
                $img_name = $rows['img_name'];
                $img_src = $rows['img_path'];
            ?>

                <div class="img-block">
                    <img src="<?php echo $img_src; ?>" alt="" title="<?php echo $img_name; ?>" class="img-responsive" />
                    <p class="img-title"><strong><?php echo $img_name; ?></strong></p>

                </div>

            <?php
            }
            ?>

        </div>

    </div>

    <div class="container main header-banner footer">
        <h3>I'm Faria</h3>
        <h4>Web Designer || Developer</h4>
    </div>
    <div class="card">
  
    <img src="photo/download (1).jpg">
    <img src="photo/download (2).jpg">
    <img src="photo/images.jpg">
    <img src="photo/download.jpg">
   
  
  </div>
    <div class="footer-clean">
       
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-4 col-md-3 item">
                        <h3>Services</h3>
                        <ul>
                            <li><a href="image-upload.php">Web design</a></li>
                            <li><a href="login.php">Development</a></li>
                            <li><a href="about.html">Hosting</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 col-md-3 item">
                        <h3>About</h3>
                        <ul>
                            <li><a href="index.php">Company</a></li>
                            <li><a href="about.html">Team</a></li>
                            <li><a href="reg.php">Legacy</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 col-md-3 item">
                        <h3>Careers</h3>
                        <ul>
                            <li><a href="upload.php">Job openings</a></li>
                            <li><a href="about.html">Employee success</a></li>
                            <li><a href="contact.html">Benefits</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 item social"><a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a><a href="#"><i class="icon ion-social-snapchat"></i></a><a href="#"><i class="icon ion-social-instagram"></i></a>
                        <p class="copyright">Faria's Gallery</p>
                    </div>
                </div>
            </div>
        
    </div>
    <footer>  
Thank you for visiting us  
</footer>
    <script src="main.js"></script>
</body>

</html>