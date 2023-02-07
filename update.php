<?php

session_start();
include 'config.php';
include 'func.php';

$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];

//Update data from session
$sql = "UPDATE users SET full_name='Doe' WHERE username='$username";

if ($con ->query($sql) === TRUE) {
  echo "Record updated successfully";
} else {
  echo "Error updating record: " . $con->error;
}



?>