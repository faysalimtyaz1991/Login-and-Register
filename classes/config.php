<?php


	$dbHost = 'localhost';
	$dbUser = 'root';
	$dbPass = '';
	$dbName = 'auth';



	try{
		$db = new PDO("mysql:dbhost=$dbHost;dbname=$dbName" , "$dbUser", "$dbPass");
	}catch(PDOException $e){
		echo $e->getmessage();
	}
