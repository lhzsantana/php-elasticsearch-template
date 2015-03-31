<!DOCTYPE html>
<html>

<head>

	<meta charset="UTF-8">

	<title>Telephone Generator - Admin</title>

	<link rel="stylesheet" href="../css/style.css">

</head>

<body>
	
	<div id="container">
	<?php
	
	include '../db_connection.php';
	session_start();
	
	if(isset($_POST['email'])&&isset($_POST['password'])){
			
		if($_POST['email']=='root'&&$_POST['password']=='root'){
			$_SESSION["admin_id"] = -1;
			$_SESSION["admin_name"] ="root";	
		}else{
		
			//$sql = 'SELECT * FROM Users WHERE email = ? AND password=password(?)';
			$sql = 'SELECT * FROM Users WHERE email = "'.$_POST['email'].'" AND password=password("'.$_POST['password'].'") AND is_admin=1';
			
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
			echo '41';
			
			$result = $conn->query($sql);

			echo '1';
			if ($result->num_rows > 0) {

				while($row = mysqli_fetch_assoc($result)){
					$_SESSION["admin_id"] = $row["id"];
					$_SESSION["admin_name"] = $row["name"];	
				}
			echo '2';
			}else{
				echo "Email or password is not correct";
			}
			echo '41';
		}
	}
	
	if(!isset($_SESSION['admin_id'])&&!isset($_SESSION['admin_name'])){
		
	?>
		<!--login-->
		<form class="searchform cf" method="POST">
			<input name="email" id="email" type="text" placeholder="Email">
			<input name="password" id="password" type="password" placeholder="Password">
			<br>
			<button type="submit">Login</button>
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
		</div>
	
		<!--generator-->
		<div style='padding-top:50px'>
		
			<?php
				include '../db_connection.php';
				
				$sql = "SELECT * from Users";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					
			?>
			<table>			
			<?php		
					// output data of each row
					while($row = $result->fetch_assoc()) {						
						$str_enable="Disable";
						if($row["enable"]==0){
							$str_enable="Enable";
						}	
												
						$str_admin="Admin";
						if($row["is_admin"]==0){
							$str_admin="Not Admin";
						}	
						
						echo "<tr><td>" . $row["name"]. "</td><td>" . $row["email"]. "</td><td><a href='disable_enable.php?user_id=" . $row["id"]. "&enable=" . $row["enable"]. "'>" . $str_enable."</a></td><td><a href='admin.php?user_id=" . $row["id"]. "&admin=" . $row["is_admin"]. "'>" . $str_admin."</a></td><td><a href='history.php?user_id=" . $row["id"]. "'>See history</a></td></tr>";
					}
			?>					
			</table>
			<?php
				} else {
					echo "0 results";
				}
				$conn->close();				
			?>
		</div>
	<?php
	}
	?>
	<script src="../js/index.js"></script>
	
	</div>
</body>

</html>