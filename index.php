<!DOCTYPE html>
<html>

<head>

	<meta charset="UTF-8">

	<title>Telephone Generator</title>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="../css/style.css">

</head>

<body>
	
	<div id="container">
	<?php
	
	include 'db_connection.php';
	session_start();
	
	if(isset($_GET['action'])&&$_GET['action']=="register"){
		echo "Thanks for registering. Please login.";
	}
	
	if(isset($_POST['email'])&&isset($_POST['password'])){
	
		//$sql = 'SELECT * FROM Users WHERE email = ? AND password=password(?)';
		$sql = 'SELECT * FROM Users WHERE email = "'.$_POST['email'].'" AND password=password("'.$_POST['password'].'") AND enable=1';
		
		//echo $sql;
		
		/*
		echo '0';
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $_POST['email']);
		$stmt->bind_param('s', $_POST['password']);

		echo '1';
		$stmt->execute();

		echo '2';
		$result = $stmt->get_result();
		
		echo '3';
		if ($row = $result->fetch_assoc()) {
			$_SESSION["user_id"] = $row["user_id"];
			$_SESSION["user_name"] = $row["user_name"];
		}
	
		echo '3';
		*/
		
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {

			while($row = mysqli_fetch_assoc($result)){
				$_SESSION["user_id"] = $row["id"];
				$_SESSION["user_name"] = $row["name"];	
			}
		}else{
			echo "Email or password is not correct, or your user is disabled";
		}
	}
	
	if(!isset($_SESSION['user_id'])&&!isset($_SESSION['user_name'])){
		
	?>
		<!--login-->
		<form class="searchform cf" method="POST">
			<input name="email" id="email" type="text" placeholder="Email">
			<input name="password" id="password" type="password" placeholder="Password">
			<br>
			<button type="submit">Login</button>
			<a href="register.php">New user registration</a>
		</form>
	
	<?php
	}else{
	?>
		<div style="padding:5px" id="header">
			<div style="float:left">
				<b>Telephone generator</b>
				<br> Hi
				<?php
					session_start();
					echo $_SESSION["user_name"];
				?>
				!
			</div>
			<div style="float:right">
				<form method="POST" action="logout.php">
					<button type="submit">Logout</button>
				</form>
			</div>
			<div style="margin:1px;float:right">
				<form method="POST" action="history.php">
					<button type="submit">History</button>
				</form>
			</div>
			<!--
			<div style="margin:1px;float:right">
				<form method="POST" action="upload.php">
					<button type="submit">Upload</button>
				</form>
			</div>
			-->
		</div>
	
		<!--generator-->
		<form class="searchform cf" action="phone_generator.php" method="POST">
			<p><input name="code" id="code" type="text" maxlength="3" placeholder="What is the area code?" onblur="isNumber()"></p>
			<p><input name="city" id="city" type="text" placeholder="What is the city name?"></p>
			<p><input name="size" id="size" type="text" maxlength="5" placeholder="What is the input size?" onblur="isNumber()"></p>
			
			<p class="restriction"><b>Restrictions</b></p>
			<p class="restriction"><input id="restrictions[]" disabled name="restrictions[]" type="checkbox" value="notcall">Do not call list</p>
			<p class="restriction"><input id="restrictions[]" name="restrictions[]" type="checkbox" value="fax">Fax numbers</p>
			<p class="restriction"><input id="restrictions[]" name="restrictions[]" type="checkbox" value="business">Business numbers</p>
			<p class="restriction"><input id="restrictions[]" name="restrictions[]" type="checkbox" value="landline">Land line</p>
			<p class="restriction"><input id="restrictions[]" name="restrictions[]" type="checkbox" value="wireless">Wireless</p>
			
			<button type="submit">Generate</button>
		</form>
	<?php
	}
	?>
	<script src="js/index.js"></script>
	
	</div>
</body>

</html>