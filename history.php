<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Telephone Generator</title>
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	
	<div id="container">
		
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
			<div style="margin:1px;float:right">
				<form method="POST" action="logout.php">
					<button type="submit">Logout</button>
				</form>
			</div>
			<div style="margin:1px;float:right">
				<form method="POST" action="index.php">
					<button type="submit">New list</button>
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
		
			<?php
				include 'db_connection.php';
				$user_id=$_SESSION['user_id'];
				
				$sql = "SELECT id, description, type, reg_date FROM History WHERE fk_user=".$user_id;
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					
			?>
			<table>			
			<?php
					// output data of each row
					while($row = $result->fetch_assoc()) {
						echo "<tr><td>" . $row["reg_date"]. "</td><td>" . $row["type"]. "</td><td>" . $row["description"]. "</td></tr>";
					}
			?>					
			</table>
			<?php
				} else {
					echo "0 results";
				}
				$conn->close();				
			?>
			
	<script src="js/index.js"></script>
	
	</div>
</body>

</html>