<?php
/*-- we included connection files--*/
	include "config.php";
	include "func.php";
	//Create table if not exits
	if(!isHasTable($con,"image_table")){
		createImageTable($con);
	}

	/*--- we created a variables to display the error message on design page ------*/
	$error = "";

	if (isset($_POST["btn_upload"]) == "Upload")
	{
		$file_tmp = $_FILES["fileImg"]["tmp_name"];
		$file_name = $_FILES["fileImg"]["name"];

		/*image name variable that you will insert in database ---*/
		$image_name = $_POST["img-name"];
		$description = $_POST['img-des'];

		//image directory where actual image will be store
		$file_path = "photo/".$file_name;	

	/*---------------- php textbox validation checking ------------------*/
	if($image_name == "")
	{
		$error = "Please enter Image name.";
	}

	/*-------- now insertion of image section has start -------------*/
	else
	{
		if(file_exists($file_path))
		{
			$error = "Sorry,The <b>".$file_name."</b> image already exist.";
		}
			else
			{
				$username = $_SESSION['username'];
				$result = mysqli_connect($server, $user, $pass) or die("Connection error: ");
				mysqli_select_db($result, $database) or die("Could not Connect to Database: ");
				mysqli_query($result,"INSERT INTO image_table(img_name,img_path,description,username,status)
				VALUES('$image_name','$file_path','$description','$username','pending')") or die ("image not inserted");
				move_uploaded_file($file_tmp,$file_path);
				header("Location: index.php");
				$error = "<p align=center>File ".$_FILES["fileImg"]["name"].""."<br />Image saved into Table.";
				

			}
		}
	}
?>