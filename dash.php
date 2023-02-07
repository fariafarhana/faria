<?php
include 'config.php';
include 'func.php';
session_start();
error_reporting(0);

if ($_POST['action'] && $_POST['id']) {
    if ($_POST['action'] == 'Delete') {
        $id = $_POST['id'];
        // sql to delete a record
        $sql = "DELETE FROM image_table WHERE id='$id'";
        if ($con->query($sql) === TRUE) {
            $id = $_POST['id'];
            echo "Deleted successfully - " . $id;
        } else {
            echo "Error deleting record: " . $con->error;
        }
        $con->close();
    }
    if ($_POST['action'] == 'Accept') {
        $id = $_POST['id'];
        //Updating to Database
        $sql = "UPDATE image_table SET status ='accepted' WHERE id='$id'";

        if ($con->query($sql) === TRUE) {
            echo "Successfully accepted - " . $id;
            $result = true;
        } else {
            echo "Failed to accept - " . $id;
            $result = false;
        }
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
    <style>
        .mmg {

            width: 200px;
            height: 200px;
            margin-bottom: 10px;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
            border: 6px solid #f7f7f7;
        }
    </style>

    <title>Document</title>
</head>

<body>

<h1 class="header">Dashboard</h1>
    <a href="index.php"> Back to home</a>
    <?php

    //include "config.php";


    $result = mysqli_connect($server, $user, $pass) or die("Could not connect to database.");
    mysqli_select_db($result, $database) or die("Could not select the databse.");
    $image_query = mysqli_query($result, "select id,img_name,img_path from image_table where status = 'pending'");
    while ($rows = mysqli_fetch_array($image_query)) {
        $img_name = $rows['img_name'];
        $img_src = $rows['img_path'];
    ?>

        <tr>
            <!-- other cells -->
            <td>
                <form method="post" action="">
                    <div style="display: inline-block;">
                        <img src="<?php echo $img_src; ?>" alt="" title="" class="mmg" />
                        <input type="text" style="display: none;" name="id" value="<?php echo $rows['id']; ?>" /><span> </span>
                        <input type="submit" name="action" value="Delete" />
                        <input type="submit" name="action" value="Accept" />
                    </div>

                </form>
            </td>
        </tr>

    <?php
    }
    ?>

    </div>

</body>

</html>