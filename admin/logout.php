<?php
	
	session_start();
		
	if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])){
		session_destroy();
	}
	
	header('Location: index.php');
?>