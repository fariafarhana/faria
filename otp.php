<?php
session_start();
error_reporting(0);
include 'config.php';
include 'func.php';

$username = $_SESSION['otp_user'];
$reqType = $_SESSION['otp_type'];
$otp = $_POST['otp'];
$email = "";
if (isset($_POST['submit'])) {

    //Getting otp from database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($con, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);

        $db_otp = $row['otp'];
        $email = $row['email'];
        if ($otp == $db_otp) {
            //Update otp as 0
            $sql = "UPDATE users SET otp ='0' WHERE username='$username'";
            if ($con->query($sql) === TRUE) {
                //Otp upta
            } else {
            }
            //Now go to login or password reset depend on request
            if ($reqType == "reg") {
                $_SESSION['username'] = "";
                saveError("info", "login your account");
                header("Location: login.php");
            } else {
                $_SESSION['email'] = $email;
                saveError("info", "login your account");
                header("Location: reset.php");
            }
        } else {
            echo " Opt not matched" . $otp;
        }
        //Saving login to cookie for 60 minute
        saveLoginInfo($email, $_POST['password']);
    } else {
        //echo "<script> alert('Enter correct Email')</script>";
        $_SESSION['errorType'] = "error";
        $_SESSION['errorMessage'] = "Enter correct email and password! ";
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
    <h2 class="center">Check your email and enter the code</h2>

    <form action="" method="POST">
        <div class="card">
            <input type="text" placeholder="Enter your otp code" name="otp" required>
            <button name="submit" type="submit">Next</button>


        </div>
    </form>

</body>

</html>