<?php

	include '../db_connection.php';
	
	$admin=0;
	if($_GET["admin"]=="0"){
		$admin=1;
	}
	
	$sql = "UPDATE Users set is_admin=".$admin." WHERE id=".$_GET["user_id"];
	//echo $sql;
	
	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
	
	header('Location: index.php');
?>