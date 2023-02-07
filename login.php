<?php
include 'config.php';
include 'func.php';
session_start();
error_reporting(0);


//Check if Table Exist or not
if (isHasTable($con, "users") == false) {
    createUserTable($con); //Creting table if not exist...

}
//Check login info from cookie
$email = "";
$password = "";
if(getEmail()!=null){
    $email = getEmail();
    $password = getpass();
}
//Check if request from registration page or have to clear the error session
$errorType = $_SESSION['errorType'];
if (!empty($errorType)) {
    // if (!$errorType == "info") {
    //     $_SESSION['errorType'] = "";
    //     $_SESSION['errorMessage'] = "";
    // }
}
//----------------------------------------------> If User Already Logged in go to Main Page
if (!empty($_SESSION['username'])) {
    //echo "<script> alert('You Are Already Logged in')</script>";
    // $_SESSION['errorType'] = "";
    // $_SESSION['errorMessage'] = "";
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);


    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['errorType'] = "success";
        $_SESSION['errorMessage'] = "Welcome " . $email;
        $password = "";
        
        //Saving login to cookie for 60 minute
        saveLoginInfo($email,$_POST['password']);
        
    } else {
        //echo "<script> alert('Enter correct Email')</script>";
        $_SESSION['errorType'] = "error";
        $_SESSION['errorMessage'] = "Enter correct email and password! ";
    }
}


$sql = "SELECT * FROM users";
$result = mysqli_query($con, $sql);

if ($result->num_rows > 0) // If Data Available in MySql
{
    $row = mysqli_fetch_assoc($result);
} else {
    //echo "<script>alert('Email or Passwor is Wrong!')</script>";
    //echo "h----> " . $result;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>


    <title>Login Page</title>


    <style>
       
    </style>
</head>

<body>
    <!-- Header -->
    <h1 style="text-align: center; padding: 10px;">User Login</h1>
    <!-- Error And Info Showing -->
    <div id="errorDiv" class="center success notes">
        <p style="padding-top: 10px; padding-bottom: 10px; font-size: 14px;" id="errorPara"></p>
    </div>

    <form action="" method="POST">
        <div class="card">
            <label>Username : </label>
            <input type="email" placeholder="Enter Username or email" name="email" value="<?php echo $email ?>" required>
            <label>Password : </label>
            <input type="password" placeholder="Enter Password" name="password" value="<?php echo $password?>" required>
            <button name="submit" type="submit">Login</button>
            <!-- <input type="checkbox" checked="checked"> Remember me <br>Forgot <a href="#"> password? </a> -->
            <p style="text-align: center; font-size: 14px;"> New user? <a href="reg.php">Sign Up</a></p>
            <p style="text-align: center; font-size: 14px;"> Forgot yout password? <a href="forgot.php">Reset</a></p>
        </div>
    </form>

    <!-- Error manage js code -->
    <script>
        function showError() {
            //alert("Alert");
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
                        var userName = "<?php echo $_SESSION['username']; ?>";
                        showLogingSuccess(userName);
                        //Clearing errors

                        break;
                    case 'error':
                        document.getElementById("errorDiv").classList.add('danger');
                        document.getElementById("errorPara").innerHTML = errorMessage;
                        break;
                    case 'info':
                        document.getElementById("errorDiv").classList.add('info');
                        document.getElementById("errorPara").innerHTML = errorMessage;
                        break;

                    default:
                        document.getElementById("errorDiv").style.display = "none";
                        break
                }

            }


            // fireSweetAlert();
        }

        function showLogingSuccess(user) {
            var message = "Welcome " + user;
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 2500
            })
            
            sleep(3000).then(() => { 
                location.replace("index.php");
            });
           
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        window.onload = showError();
    </script>
</body>


</html>