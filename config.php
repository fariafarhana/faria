<?php 

$server = "localhost";
$user = "root";
$pass = "";
$database = "test";

$con = mysqli_connect($server, $user, $pass, $database);

if (!$con) {
    die("<script>alert('Connection Failed.')</script>");
}else{
 //echo "<script>alert('Connected')</script>" ;  
}

function isHasTable($con,$table_){
    $c = false;
$database = "test";
    $showtables= mysqli_query($con, "SHOW TABLES FROM $database");
    while($table = mysqli_fetch_array($showtables)) { 
     //echo($table[0] . "<br>");//Printing Table
     if($table[0]==$table_){
         $c= true;
     }
    }
    return $c;
}
?>