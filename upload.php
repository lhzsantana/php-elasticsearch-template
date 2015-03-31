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
		</div>
	
		 <?php
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
		?> 
		
		<!--uploader-->
		
		<form  class="results cf"  action="upload.php" method="post" enctype="multipart/form-data">
			Select image to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
			<button type="submit">Upload</button>
		</form>
		
	<script src="js/index.js"></script>
	
	</div>
</body>

</html>