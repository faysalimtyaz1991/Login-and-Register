<?php include('header.php'); ?>



	<?php

		if( !isset($_SESSION['reset_userid']) ){
			header("Location: login.php");
		}

		
		include('classes/config.php');
		include('classes/token.php');
		include('classes/forgotPassword.php');
		$Forgot = new forgotPassword();

		$errorList = '';
		if( isset($_POST['resetpass']) ){
			$response = $Forgot->ResetPassword($_POST);

			
			if( empty($response) ){
				$errorList = '<div class="alert alert-success">
						Success
					</div>';

				header("Location: new_password.php");
			}else{
				foreach( $response as $message ){
					
						$errorList = '<div class="alert alert-danger">
							
							'.$message.'

						</div>';
					
				}
			}
		}

		

	?>

	
	<div class="col-md-5 col-md-offset-3 accessform">
		<h1 class="form-header">Password Recovery</h1>

		<?=$errorList?>


		<form action="new_password.php" method="post">
			
			<div class="form-group">
				<label for="exampleInputEmail1">Password</label>
				<input type="password" class="form-control" value="" name="password" />
			</div>

			<div class="form-group">
				<label for="exampleInputEmail1">ConfirmPassword</label>
				<input type="password" class="form-control" value="" name="cpassword" />
			</div>

			<div class="form-group">
				<input type="submit" value="Reset Password" name='resetpass' class='btn btn-default' />
			</div>
		</form>


	</div>



<?php include('footer.php'); ?>