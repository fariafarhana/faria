<?php
include 'config.php';
include 'func.php';
session_start();
error_reporting(0);
//--------------------------------------------> Checking if Account Already Exist
if (isset($_SESSION['username'])) {
    header("Location: index.php");
}
$_SESSION['errorType'] = "";


$username = mysqli_real_escape_string($con, $_POST['username']);
$email = $_POST['email'];
$password = md5($_POST['password']);
$cpassword = md5($_POST['cpassword']);
$full_name = $_POST['full_name'];
$v = "";

//------------------------------------------> Check if password, Then go Ahead
if (isset($_POST['submit'])) {
    if (isValidData()) {

        if ($password == $cpassword) { //1-1-> If Password Match
            $sql = "SELECT * FROM users WHERE username = '$username' OR email ='$email'";
            $result = mysqli_query($con, $sql);
            if (!$result->num_rows > 0) { //2-1-> User Name and Email is not Eist
                $sql = "INSERT INTO users (full_name, username, email, password)
					VALUES ('$full_name','$username', '$email', '$password')";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    $_SESSION['errorType'] = "info";
                    $_SESSION['errorMessage'] = "Welcome " . $full_name . " <br>Check your email to verify";
                    $_SESSION['otp_user'] = $username;
                    $_SESSION['otp_type'] = 'reg'; //For 

                    $code = getOtp();
                    $ms = sendMail($email, "OTP CODE", "Your code is : " . $code);
                    //updating otp to database
                    $sql = "UPDATE users SET otp ='$code' WHERE username='$username'";
                    if ($con->query($sql) === TRUE) {
                        //Otp upta
                    } else {
                        
                    }
                    
                   
                    if ($ms) {
                        $username = "";
                        $email = "";
                        $full_name = "";
                        $_POST['password'] = "";
                        $_POST['cpassword'] = "";
                        $_POST['full_name'] = "";
                       
                    } else {
                       
                    }


                    // echo "<script>alert('Registration Successfull/Login Here')</script>";
                    //header("Location: index.php");
                } else {
                    //echo "<script>alert('Registration Unuccessfull ')</script>";
                    $_SESSION['errorType'] = "error";
                    $_SESSION['errorMessage'] = "Sever problem. Try later";
                }
            } else { //2-0-> User Name and Email it Eist

                $_SESSION['errorType'] = "error";
                $_SESSION['errorMessage'] = "Email or Username already exist";
            }
        } else { //1-0-> Password did not match... message to give same password
            //echo "<script>alert('password not matched')</script>";
            $_SESSION['errorType'] = "error";
            $_SESSION['errorMessage'] = "Password did not match!";
        }
    }
} else {
    //Data is Invalid
}

function isValidData()
{
    $isOk = true;
    //Check Full Name
    $errorMessage = "";
    if (!preg_match("/^[a-zA-Z-' ]{3,31}$/", $_POST['full_name'])) {
        $isOk = false;
        $errorMessage .= "<strong> Full Name : </strong> Only letters and white space allowed<br>";
    }
    //Check user name 
    if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST['username'])) {
        $isOk = false;
        $errorMessage .= "<strong> Username : </strong> Only number and character are allowed <br>";
    }
    //Check Password
    if (!preg_match('/^[a-zA-Z0-9!@#$%^&*]{6,16}$/', $_POST['password'])) {
        $isOk = false;
        $errorMessage .= "<strong> Password : </strong>Use character, number and minimum length 6<br>";
    }


    if (!$isOk) {
        $_SESSION['errorType'] = "error";
        $_SESSION['errorMessage'] = $errorMessage;
        return false;
    }

    return true;
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="libs/cute-alert/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <title>Registration</title>

</head>

<body style="font-family: sans-serif;">
    <!-- Heading -->
    <h1 style="text-align: center; padding: 10px;">New User Registration</h1>
    <!-- Error And Info Showing -->
    <div id="errorDiv" class="center success notes">
        <p style="padding-top: 10px; padding-bottom: 10px; font-size: 14px;" id="errorPara"></p>
    </div>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

        <div class="card">

            <label>Name : </label>
            <input type="text" placeholder="Enter Your Full name" name="full_name" value="<?php echo $_POST['full_name']; ?>" required>
            <label>Username : </label>
            <input type="text" placeholder="Enter a Username" name="username" value="<?php echo $_POST['username']; ?>" required>
            <label>Email : </label>
            <input type="email" placeholder="Enter a email" name="email" value="<?php echo $_POST['email']; ?>" required>
            <label>Password : </label>
            <input type="password" placeholder="Enter Password" name="password" value="<?php echo $_POST['password']; ?>" required>
            <label>Confirm Password : </label>
            <input type="password" placeholder="Confirm your password" name="cpassword" value="<?php echo $_POST['cpassword']; ?>" required>
            <input type="submit" name="submit">Registration</button>

            <p style="text-align: center; font-size: 14px;"> Have account? <a href="login.php">login</a></p>
        </div>
    </form>


    <script src="libs/cute-alert/app.js">
    </script>
    <script>
        function showError() {

            var errorType = "<?php echo $_SESSION['errorType']; ?>";
            var errorMessage = "<?php echo $_SESSION['errorMessage']; ?>";
            if (errorType == "") { //No Error Available
                document.getElementById("errorDiv").style.display = "none";
            } else {
                document.getElementById("errorDiv").style.display = "block";
                document.getElementById("errorDiv").className = '';
                document.getElementById("errorDiv").classList.add('center');
                document.getElementById("errorDiv").classList.add('notes');

                switch (errorType) {
                    case 'success':
                        document.getElementById("errorDiv").classList.add('success');
                        document.getElementById("errorPara").innerHTML = errorMessage;
                        <?php
                        // Change error Type to info for login
                        $_SESSION['errorType'] = "info";
                        $_SESSION['errorMessage'] = "Please login to confirm your account";
                        ?>
                        fireSweetAlert();
                        break;
                    case 'error':
                        document.getElementById("errorDiv").classList.add('danger');
                        document.getElementById("errorPara").innerHTML = errorMessage;
                        break;
                    case 'info':
                        document.getElementById("errorDiv").classList.add('info');
                        document.getElementById("errorPara").innerHTML = errorMessage;
                        fireSweetAlert();

                        break;

                    default:
                        document.getElementById("errorDiv").style.display = "none";
                        break
                }

            }

        }

        function fireSweetAlert() {
            Swal.fire(
                'Registration Sucessful',
                'Please verify your account',
                'success'
            ).then((result) => {
                if (result.value) {
                    location.replace("otp.php")
                }
            });
        }

        window.onload = showError();
    </script>
</body>

</html>