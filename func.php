<?php
function createImageTable($conn){
    $sql = "CREATE TABLE image_table (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        img_name  VARCHAR(50) NOT NULL,
        img_path  VARCHAR(100) NOT NULL,
        description  TEXT,
        status  VARCHAR(20) NOT NULL,
        username TEXT,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($sql) === TRUE) {
          echo "Table  created successfully";
        } else {
          echo "Error creating table: " . $conn->error;
        }
}
function createUserTable($conn){
  $sql = "CREATE TABLE users (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      full_name VARCHAR(30) NOT NULL,
      username VARCHAR(30) NOT NULL,
      email VARCHAR(50),
      password VARCHAR (100) NOT NULL,
      role TEXT,
      otp VARCHAR(20),
      reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      )";
      
      if ($conn->query($sql) === TRUE) {
        echo "Table MyGuests created successfully";
      } else {
        echo "Error creating table: " . $conn->error;
      }

}
function updateToUsers($conn, $columnName, $value, $number)
{
    $sql = "UPDATE users SET $columnName = '$value' WHERE number = '$number'";

    if ($conn->query($sql) === TRUE) {
        //echo "MySQL Data Updated<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function saveError($errorType,$errorMessage){
  $_SESSION['errorType'] = $errorType;
  $_SESSION['errorMessage'] = $errorMessage;

}

function getpass(){
  $password = $_COOKIE['password'];;
  if(isset($password)){ 
    return $password;
  }else{
    return null;
  }
}
function getEmail(){
  $password = $_COOKIE['email'];;
  if(isset($password)){ 
    return $password;
  }else{
    return null;
  }
}
function saveLoginInfo($email,$password){
  saveCookie('email',$email,60*60);
  saveCookie('password',$password,60*60);
}

function saveCookie($name,$vale,$expireTime){
  setcookie(              //set coocki is builtin fuction to set cookie
    $name,          //First parameter is Cooke name
    $vale,         //Coocke value
    time()+$expireTime,  //when coocke will wxpire ()
    "/"); 
}

function getOtp(){
  $code = rand(999999,111111);
  // $code = 'abcdABCD0123456780+-*/$#@';
  $code = str_shuffle($code);
  $code = substr($code, 0,10);
  return $code;
}
function sendMail($email,$subject,$body){
  $receiver = $email;
  $sender = "From:ovi.bookmarks@gmail.com";
      if(mail($receiver, $subject, $body, $sender)){
          return true;
      }else{
          return false;
      }
  }
?>