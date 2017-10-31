<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>login and Registration System</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">

	<link rel="stylesheet" href="includes/styles/style.css" type="text/css">

</head>
<body>
	
	
	


	<div class="container">


		<div class="row">
		
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="#">WebSiteName</a>
		    </div>
		    <ul class="nav navbar-nav pull-right">
		    	<?php
		    		session_start();
		    		if( isset($_SESSION['user']) or isset($_COOKIE['user']) ){
						echo '<li><a href="logout.php">Logout</a></li>';
					}else{
						echo '<li><a href="register.php">SignUp</a></li>
		      					<li><a href="login.php">Login</a></li>';
					}
		    	?>
		      
		    </ul>
		  </div>
		</nav>
		

	</div>