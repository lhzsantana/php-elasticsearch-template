<?php

	include '../db_connection.php';
	
	$enable=0;
	if($_GET["enable"]=="0"){
		$enable=1;
	}
	
	$sql = "UPDATE Users set enable=".$enable." WHERE id=".$_GET["user_id"];
	//echo $sql;
	
	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
	
	header('Location: index.php');
?>