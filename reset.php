<?php
session_start();
error_reporting(0);
include 'config.php';
include 'func.php';

$password = $_SESSION['password'];
$cpassword = $_SESSION['cpassword'];
$email = $_SESSION['email'];

if (isset($_POST['submit'])) {
    if($password == $cpassword){
        $enpass = md5($_POST['cpassword']);
        $sql = "UPDATE users SET password ='$enpass' WHERE email='$email'";
        if ($con->query($sql) === TRUE) {
            saveError("info","Login to confirm your account");
            header("Location: login.php");
        } else {
            echo "password can't updated";
        }

    }else{
        echo "password not match!";
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
    <title>Password Reset</title>
</head>

<body>
    <h3 class="header">Reset password</h3>
    <form action="" method="POST">
        <div class="header">
            <input type="password" placeholder="Enter new password" name="password" required>
            <input type="password" placeholder="Confirm password" name="cpassword" required>
            <button name="submit" type="submit">Change</button>


        </div>
    </form>

</body>

</html>