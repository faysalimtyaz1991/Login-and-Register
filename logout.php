<?php

	
	session_start();

	//unset($_SESSION['user']);

	session_destroy();

	setcookie('user', '', time() - 86400, '/'); //120 = 2 minutes
	

	header("Location: index.php");
	

?>