<?php
	
	try {
		//echo "Trying to connect";
		
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "kubal";
		
		$conn = mysqli_connect($servername, $username, $password, $dbname) or die ("could not connect to mysql");

		//echo "Not connect";
	
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		//echo "Connected successfully";
	
	} catch (Exception $e) {
		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
	}

?>