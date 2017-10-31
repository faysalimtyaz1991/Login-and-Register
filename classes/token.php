<?php


	class Token{

		public static function generate()
		{
			return $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(30));
		}



		public function check($token)
		{

			if( $token == $_SESSION['token'] ){
				return true;
			}

			return false;

		}

	}