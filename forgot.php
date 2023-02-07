<?php
session_start();
error_reporting(0);
include 'config.php';
include 'func.php';

if (isset($_POST['submit'])) {

    //Getting otp from database
    $email = $_POST['email'];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($con, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
        $_SESSION['otp_user'] = $username;
        $_SESSION['otp_type'] = 'pas'; //For  password reset

        $code = getOtp();
        $ms = sendMail($email, "Password Code", "Your code is : " . $code);

        if ($ms) {
            //updating otp to database
            $sql = "UPDATE users SET otp ='$code' WHERE username='$username'";
            if ($con->query($sql) === TRUE) {
                //Otp upta
                 //updating otp to database
                    $sql = "UPDATE users SET otp ='$code' WHERE username='$username'";
                    if ($con->query($sql) === TRUE) {
                        //Otp upta
                        header("Location: otp.php");
                    } else {
                        
                    }
                    
            } else {
            }
        } else {
            echo "Try again";
        }
    } else {
        echo "<script> alert('Email is not exist')</script>";
        $_SESSION['errorType'] = "error";
        $_SESSION['errorMessage'] = "Email is not exist ";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Account Verification</title>
</head>

<body>
    <h2 style = "text-align: center;">To reset password enter your email</h2>

    <form action="" method="POST">
        <div class="card">
            <input type="text" placeholder="Enter your email" name="email" required>
            <button name="submit" type="submit">Send OTP</button>


        </div>
    </form>

</body>

</html>