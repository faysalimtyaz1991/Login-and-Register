<?php include('header.php'); ?>



	<?php


		include('classes/config.php');
		include('classes/token.php');
		include('classes/forgotPassword.php');
		$Forgot = new forgotPassword();

		
		
		$errorList = '';
		if( isset($_POST['verification']) ){
			$response = $Forgot->VerifyCode($_POST);

			
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

		
		
		if( isset($_POST['recovery']) ){

			$response = $Forgot->SendVerification($_POST);

			if( empty($response) ){
				
	
					$errorList = '<div class="alert alert-success">
						Email send Successfully! Please check your inbox
					</div>';

					header("Location: forgot.php");

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

		<?php
		if( isset($_COOKIE['verification_code']) ){ 
		?>

		<form action="forgot.php" method="post">
			
			<div class="form-group">
				<label for="exampleInputEmail1">Verification Code</label>
				<input type="text" class="form-control" id="exampleInputEmail1" value="" name="vcode" placeholder="######">
			</div>

			<div class="form-group">
				<input type="submit" value="Submit" name='verification' class='btn btn-default' />
			</div>
		</form>

		<?php }else{ ?>

		<form action="forgot.php" method="post">
			
			<div class="form-group">
				<label for="exampleInputEmail1">Email address</label>
				<input type="email" class="form-control" id="exampleInputEmail1" value="" name="email" placeholder="Email Address">
			</div>

			<div class="form-group">
				<input type="submit" value="Send Verification Code" name='recovery' class='btn btn-default' />
			</div>



		</form>

		<?php } ?>

	</div>



<?php include('footer.php'); ?>