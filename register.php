<?php include('header.php'); ?>



	<?php

		include('classes/config.php');
		include('classes/token.php');
		include('classes/user.php');
		$User = new User();



		$errorList = '';
		if( isset($_POST['register']) ){

			$response = $User->registerUser($_POST);

			if( empty($response) ){
				
	
					$errorList = '<div class="alert alert-success">
						User has been successfully registered.
					</div>';

				
			}else{

				foreach( $response as $message ){
					
						$errorList = '<div class="alert alert-danger">
							
							'.$message.'

						</div>';
					
				}

			}

		}

	?>

	<?php


		include('fb.php');
		include('google.php');

	?>

	
		
	<div class="col-md-5 col-md-offset-3 accessform">
		<h1 class="form-header">Signup from here</h1>

		<?=$errorList?>


		<form action="register.php" method="post">
			
			<div class="form-group">
				<label for="exampleInputText">Name</label>
				<input type="text" class="form-control" id="exampleInputText" value="<?=$User->name?>" name="fullname" placeholder="Enter Name">
			</div>

			<div class="form-group">
				<label for="exampleInputEmail1">Email address</label>
				<input type="email" class="form-control" id="exampleInputEmail1" value="<?=$User->email?>" name="email" placeholder="Email">
			</div>

			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" class="form-control" id="exampleInputPassword1" value="<?=$User->password?>" name="password" placeholder="Password">
			</div>

				
			<input type="hidden" class="form-control" value="<?=Token::generate()?>" name="token" id="exampleInputText" placeholder="Enter Name">

			<div class="form-group">
				<input type="submit" value="Signup" name='register' class='btn btn-default' />
				<span>Already have an account ? <a href="login.php">Login</a></span>
			</div>

			<hr />

			<p>
				
				<?=$output?>

			</p>

		</form>
	
	</div>

	
<?php include('footer.php'); ?>