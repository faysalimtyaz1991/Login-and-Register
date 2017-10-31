<?php

	
	$token = $_GET['token'];


	include('classes/config.php');

	$query = $db->prepare('SELECT * from users where token=?');
	$query->execute([$token]);



	if( $query->rowCount()==1 ){

		$update = $db->prepare("UPDATE users SET active=? where token=?");
		$update->execute([1, $token]);

		echo 'You have successfully activated your account. Please <a href="login.php">Login</a>';

	}