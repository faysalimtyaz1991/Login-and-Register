<?php


	include('classes/config.php');

	$query = $db->prepare("Update users set active=? where id=?");
	$query->execute(['0', '9']);


