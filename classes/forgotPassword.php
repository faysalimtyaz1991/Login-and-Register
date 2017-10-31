<?php



 	class forgotPassword{

 		public $error = [];
 		public $rs;


 		public function ResetPassword($postData)
 		{
 			global $db;

 			$password = $postData['password'];
 			$cpassword = $postData['cpassword'];

 			if( empty($password) or empty($cpassword) ){
 				$this->addError("Please fill out all fields");
 			}else{

 				if( $password != $cpassword ){
 					$this->addError("Both passwords does not match");
 				}else{

 					$hashed = password_hash($password, PASSWORD_DEFAULT, ["cost"=>12]);

 					$query = $db->prepare("UPDATE users Set password=? where id=?");

					$query->execute([$hashed, $_SESSION['reset_userid']]);

					unset($_SESSION['reset_userid']);


					$this->error = [];

 				}

 			}


 			return $this->error;

 		}

 		public function SendVerification($postData)
		{

			if( !$this->Validation($postData) ){
				return $this->error;
			}


			if( $this->check($postData) ){

				$postData['user_id'] = $this->rs['id'];

				if( $this->Send($postData) ){
					$this->error = [];
				}

			}else{
				$this->addError('Sorry! This email address does not exists');
			}


			return $this->error;

		} 	



		public function VerifyCode($postData)
		{
			global $db;

			$code = base64_encode(md5($postData['vcode']));

			if( !isset($_COOKIE['verification_code']) ){

				$this->addError('Please try again with new verification Code.');

			}else{

				if( $_COOKIE['verification_code'] == $code ){


					// check if verification code exists in my db
					$query = $db->prepare("SELECT id from users WHERE verification_code=?");
					$query->execute([$code]);

					if( $query->rowCount()==1 ){

						$details = $query->fetch();

						// store user id in session
						$_SESSION['reset_userid'] = $details['id'];
						
						setcookie('verification_code', '', time()-120, '/');

						$query = $db->prepare("UPDATE users Set verification_code=? where id=?");

						$query->execute(['', $_SESSION['reset_userid']]);

						$this->error = [];

					}



				}else{
					$this->addError('Please try again with new verification Code.');
				}

			}
			

			return $this->error;

		}	






		private function check($postData)
		{
			global $db;

			$query = $db->prepare("SELECT id from users WHERE email=?");
			$query->execute([$postData['email']]);

			if( $query->rowCount()==1 ){

				$this->rs = $query->fetch();

				return true;
			}

			return false;
		}




		private function Send($postData)
		{

			global $db;
			$token = rand(9999, 1000000);

			$to = $postData['email'];
			$subject = 'Verification Code | Forget Password';
			$body = 'Copy and Paste the given verfication code below ';
			$body .= $token;

			$headers = "From: test@gmail.com\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";


			if( mail($to, $subject, $body, $headers) ){

				$etoken = base64_encode(md5($token));

				setcookie('verification_code', $etoken, time()+120, '/'); //120 = 2 minutes

				// storing in db
				$query = $db->prepare("UPDATE users Set verification_code=? where id=?");
				$query->execute([$etoken, $postData['user_id']] );

				return true;
			}

			return false;

		}




		protected function Validation($postData)
		{
			if( empty($postData['email']) ){

				$this->addError('Please fill out email address');

			}else{

				if( !filter_var($postData['email'], FILTER_VALIDATE_EMAIL) ){

					$this->addError('Please enter valid email address');

				}else{
					return true;
				}
			}

			return false;
		}


		public function addError($message)
		{
			array_push($this->error, $message);
		}


 	}