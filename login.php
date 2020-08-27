<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Callum Harding">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					$id = $row['user_id'];
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>
							<h1>Gallery</h1>
							<div class ='row imageGallery'>";
							
							$qry = "SELECT * FROM tbgallery WHERE user_id = '$id'";
							$out = mysqli_query($mysqli, $qry);
							while ($output = mysqli_fetch_array($out)) 
							{
								echo 
								"
								<div class='col-3' style='background-image: url(gallery/".$output["filename"].")'></div>
								</div>";
							}	
				
					echo 	"<form action='login.php' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									 <input type='hidden' id='loginPass' name='loginPass' value=" .$_POST["loginPass"] .">
									 <input type='hidden' id='loginEmail' name='loginEmail' value=" .$_POST["loginEmail"] .">
								</div>
						  	</form>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
			if(isset($_POST['submit']))
			{
				$uploadedFile = $_FILES['picToUpload']['name'];
			if(($_FILES['picToUpload']['type'] == "image/jpg" || $_FILES['picToUpload']['type'] == "image/jpeg") && $_FILES['picToUpload']['size'] < 1000000){
				if($_FILES['picToUpload']['error'] > 0)
				{
					echo "Error: " .$_FILES['picToUpload']['error'] ."<br/>";
				}
				else{
					move_uploaded_file($_FILES['picToUpload']['tmp_name'],
					"gallery/" .$_FILES['picToUpload']['name']);
					$query2 = "INSERT INTO tbgallery (user_id, filename) VALUES ('$id','$uploadedFile');";
					$res = mysqli_query($mysqli, $query2) == TRUE;
				}
			}
			else{
				echo "The file you uploaded was not a supported type. <br />";
			}
		}
		?>
	</div>
</body>
</html>