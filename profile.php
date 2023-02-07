<?php
include 'config.php';
include 'func.php';
session_start();
error_reporting(0);
$full_name = "";
$username = "";
$email="";
//--------------------------------------------> Checking if Account Already Exist
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE  username='$username'";
    $result = mysqli_query($con, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $full_name = $row['full_name'];
        $email = $row['email'];
        $_SESSION['errorType'] = "success";
        $_SESSION['errorMessage'] = "Welcome " . $email;
    } else {

        $_SESSION['errorMessage'] = "Enter correct email and password! ";
    }
    // header("Location: index.php");
}else{
    $_SESSION['errorType'] = "info";
    $_SESSION['errorMessage'] = "Please login your account";
    header("Location: login.php");
}
$_SESSION['errorType'] = "";


//------------------------------------------> Check if password, Then go Ahead
if (isset($_POST['submit'])) {
    if (isValidData()) {

       
        $email = $_POST['email'];
        $cpassword = md5($_POST['cpassword']);
        $full_name = $_POST['full_name'];
        $nuser = $_POST['username'];
        $v = "";
        $sql = "SELECT * FROM users WHERE username = '$username' OR email ='$email'";
        $result = mysqli_query($con, $sql);
        if ($result->num_rows > 0) { //2-1-> User Name and Email is not Eist
            $sql = "UPDATE users SET full_name='$full_name', username='$nuser', email='$email' WHERE username='$username'";

            if ($con ->query($sql) === TRUE) {
              //echo "Record updated successfully";
              $result = true;
            } else {
              
              $result = false;
            }
            if ($result) {
                $_SESSION['errorType'] = "success";
                $_SESSION['errorMessage'] = "Successfully Updated!";

                $username = "";
                $email = "";
                $full_name = "";
                $_POST['password'] = "";
                $_POST['cpassword'] = "";
                $_POST['full_name'] = "";
                $_SESSION['username'] = $nuser;
                // echo "<script>alert('Registration Successfull/Login Here')</script>";
                //header("Location: index.php");
            } else {
                //echo "<script>alert('Registration Unuccessfull ')</script>";
                $_SESSION['errorType'] = "error";
                $_SESSION['errorMessage'] = "Sever problem. Try later";
            }
        } else { //2-0-> User Name and Email it Eist

            $_SESSION['errorType'] = "error";
            $_SESSION['errorMessage'] = "Who are you?";
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
    <title>Profime</title>

</head>

<body style="font-family: sans-serif;">
    <!-- Heading -->
    <h1 style="text-align: center; padding: 10px;">Hey <?php echo $username;?> Update your profile</h1>
    <!-- Error And Info Showing -->
    <div id="errorDiv" class="center success notes">
        <p style="padding-top: 10px; padding-bottom: 10px; font-size: 14px;" id="errorPara"></p>
    </div>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

        <div class="card">

            <label>Full Name </label>
            <input type="text" placeholder="Enter Your Full name" name="full_name" value="<?php echo $full_name; ?>" required>
            <label>User Name</label>
            <input type="text" placeholder="Enter a Username" name="username" value="<?php echo $username; ?>" required>
            <label>Email : </label>
            <input type="email" placeholder="Enter a email" name="email" value="<?php $email; ?>" required>
            <!-- <label>Password : </label> -->
            <!-- <input type="password" placeholder="Enter Password" name="password" value="<?php echo $_POST['password']; ?>" required>
            <label>Confirm Password : </label>
            <input type="password" placeholder="Confirm your password" name="cpassword" value="<?php echo $_POST['cpassword']; ?>" required> -->
            <button style="background-color: #333;" type="submit" name="submit">Update Profile</button>

            <p style="text-align: center; font-size: 14px;"> <a href="index.php">Back To Home</a></p>
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
                        break;

                    default:
                        document.getElementById("errorDiv").style.display = "none";
                        break
                }

            }

        }

        function fireSweetAlert() {
            Swal.fire(
                'Successfully Updated',
                'keep private your persoal info',
                'success'
            ).then((result) => {
                if (result.value) {
                    location.replace("index.php")
                }
            });
        }

        window.onload = showError();
    </script>
</body>

</html>