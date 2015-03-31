<!DOCTYPE html>
<html>

<head>

	<meta charset="UTF-8">

	<title>Telephone Generator</title>

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	
	<div id="container">
	<?php
		
		if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])){
			
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					echo "Invalid email";
			}else{
				
				if($_POST['password']==$_POST['repeat_password']){

					include 'db_connection.php';
					
					$enable=1;
					if (strpos($_POST['email'],'.edu') == false) {
						$enable=0;
					}
				
					$sql = "INSERT INTO Users (name, email, password, reg_date, is_admin, enable) VALUES ('".$_POST['name']."','".$_POST['email']."',password('".$_POST['password']."'),now(),0,".$enable.")";
					//echo $sql;
					
					if (mysqli_query($conn, $sql)) {
						echo "New record created successfully";
						
						//send welcome email
						$to = $_POST['email'];
						$subject = "Thanks for registering";
						$txt = "Thanks for your registration!";
						$headers = "From: webmaster@example.com" . "\r\n";
						
						if($enable==0){
							
							$sql = "SELECT * FROM Users WHERE is_admin=1";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
								while($row = $result->fetch_assoc()) {
									//send email to the admins
									$to = $row["email"];
									$subject = "New non .edu user registered";
									$txt = "You have to enable the user ".$_POST['name']." manually";
									$headers = "From: webmaster@example.com" . "\r\n";
								}
							}
						}

						mail($to,$subject,$txt,$headers);
						
						header('Location: index.php?action=register');
					} else {
						//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
						echo mysqli_error($conn);
					}

					mysqli_close($conn);
				}else{
					echo "The password was not correctly repeated";
				}
			}
		}
	?>
		<!--login-->
		<form class="searchform cf" method="POST">
			<input name="name" id="name" type="text" placeholder="Name">
			<input name="email" id="email" type="text" placeholder="E-mail">
			<input name="password" id="password" type="password" placeholder="Password">
			<input name="repeat_password" id="repeat_password" type="password" placeholder="Repeat your password">
			<br>
			<button type="submit">Register</button>
		</form>
	
	<script src="js/index.js"></script>
	
	</div>
</body>

</html>