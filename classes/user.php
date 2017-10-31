<?php


	class User
	{

		public $error = [];

		public $name;
		public $email;
		public $password;

		public $loginUser;

		public function __construct()
		{

			if( isset($_SESSION['user']) or isset($_COOKIE['user']) ){
				header("Location: index.php");
			}

		}

		public function registerUser($postData)
		{
			global $db;

			if( !Token::check($postData['token']) ){
				$this->addError('Invalid Request');

				return $this->error;
			}

			$this->name = $postData['fullname'];
			$this->email = $postData['email'];
			$this->password = $postData['password'];

			
			if( $this->Validation($postData) ){

				if( $this->emailExists('register', $postData) ){

					$this->addError('Sorry this email already exists in the system . Please try something else');

				}else{
					$token = md5(uniqid(rand(), true));

					$query = $db->prepare("Insert into users Set name=?, email=?, password=?, token=?");

					$hashed = password_hash($postData['password'], PASSWORD_DEFAULT, ["cost"=>12]);

					$query->execute([$postData['fullname'], $postData['email'], $hashed, $token]);


					$postData['token'] = $token;

					$this->SendConfirmationEmail($postData);

					$this->error = [];

					$this->name = '';
					$this->email = '';
					$this->password = '';
				}

				

				return $this->error;

			}else{
				return $this->error;
			}
		}

		public function loginUser($postData)
		{

			if( !Token::check($postData['token']) ){
				$this->addError('Invalid Request');

				return $this->error;
			}

			$this->email = $postData['email'];
			$this->password = $postData['password'];

			if( $this->Validation($postData) ){

				if( $this->emailExists('login', $postData) ){

					if( isset($postData['rememberme']) ){
						setcookie('user', $this->loginUser->id, time() + 86400, '/'); //120 = 2 minutes
					}

					//store session
					$_SESSION['user'] = $this->loginUser->id;
					$_SESSION['name'] = $this->loginUser->name;


					

					header("Location: index.php");

					$this->error = [];

				}else{
					$this->addError('Sorry! Email and Password combination does not match.');
				}

			}

			return $this->error;

		}






		public function emailExists($type, $postData)
		{
			global $db;

			if( $type=='register' ){
				$query = $db->prepare("SELECT id from users WHERE email=?");
				$query->execute([$postData['email']]);

				if( $query->rowCount()>=1 ){
					return true;
				}

			}else if($type=='login'){

				$query = $db->prepare("SELECT * from users WHERE email=? and active=?");

				$query->execute([$postData['email'], '1']);		

				if( $query->rowCount()>=1 ){
					$this->loginUser = $query->fetchObject();

					if( password_verify($postData['password'], $this->loginUser->password) ){
						return true;
					}

				}		

			}

			
			return false;

			

		}



		protected function SendConfirmationEmail($postData)
		{

			$token = $postData['token'];

			$to = $postData['email'];
			$subject = 'Confirmation Email | Please activate your account';
			$body = 'Click at the below link in order to activate your account ';
			$body .= '<a href="http://localhost/auth/activate.php?token='.$token.'">Click here</a>';

			$headers = "From: test@gmail.com\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\n";


			if( mail($to, $subject, $body, $headers) ){
				return true;
			}

		}





		public function addError($message)
		{
			array_push($this->error, $message);
		}


		protected function Validation($postData)
		{
			if( (isset($postData['fullname']) and empty($postData['fullname'])) or empty($postData['email']) or empty($postData['password']) ){

				$this->addError('Please fillout all the fields in order to signup');

			}else{

				if( !filter_var($postData['email'], FILTER_VALIDATE_EMAIL) ){

					$this->addError('Please enter valid email address');

				}else{

					if( isset($postData['fullname']) and strlen($postData['fullname'])<3 ){
						$this->addError('Please enter more then 3 charactors');
					}elseif( strlen($postData['password'])<4 ){
						$this->addError('Please enter atleast 6 charactors');
					}else{
						

						return true;
					}
				}
			}

			return false;
		}




	}