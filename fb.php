<?php

	require 'src/Facebook/autoload.php';

	$fb = new \Facebook\Facebook([
	  'app_id' => '401590096902646',
	  'app_secret' => '4f29c8578a896ae476f2dac41470b5e2',
	  'default_graph_version' => 'v2.10',
	  //'default_access_token' => '{access-token}', // optional
	]);

	// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
	//   $helper = $fb->getRedirectLoginHelper();
	//   $helper = $fb->getJavaScriptHelper();
	//   $helper = $fb->getCanvasHelper();
	//   $helper = $fb->getPageTabHelper();
	$helper = $fb->getRedirectLoginHelper();
	try {

		if(isset($_SESSION['facebook_access_token'])){
			$accessToken = $_SESSION['facebook_access_token'];
		}else{
			  $accessToken = $helper->getAccessToken();
		}
	  // Get the \Facebook\GraphNodes\GraphUser object for the current user.
	  // If you provided a 'default_access_token', the '{access-token}' is optional.
	  //$response = $fb->get('/me', $accessToken);
	} catch(\Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(\Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	}

	//$me = $response->getGraphUser();
	//echo 'Logged in as ' . $me->getName();

	$redirectURL   = 'http://localhost/auth/login.php'; //Callback URL
	$fbPermissions = array('email');  //Optional permissions


	if(isset($accessToken)){
		if(isset($_SESSION['facebook_access_token'])){
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		}else{
			// Put short-lived access token in session
			$_SESSION['facebook_access_token'] = (string) $accessToken;
			
			  // OAuth 2.0 client handler helps to manage access tokens
			$oAuth2Client = $fb->getOAuth2Client();
			
			// Exchanges a short-lived access token for a long-lived one
			$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
			$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
			
			// Set default access token to be used in script
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		}
		
		// Redirect the user back to the same page if url has "code" parameter in query string
		if(isset($_GET['code'])){
			header('Location: ./');
		}
		
		// Getting user facebook profile info
		try {
			$profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
			$fbUserProfile = $profileRequest->getGraphNode()->asArray();
		} catch(FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			session_destroy();
			// Redirect user back to app login page
			header("Location: ./");
			exit;
		} catch(FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		

		

		

	   $_SESSION['user'] = $fbUserProfile['id'];
	   $_SESSION['name'] = $fbUserProfile['first_name'].' '.$fbUserProfile['last_name'];
	   
		
		// Render facebook profile data
		$output = '';
		if(empty($fbUserProfile)){
			$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
		}
		
	}else{
		// Get login url
		$loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
		
		// Render facebook login button
		$output = '<a class="btn btn-primary social-btn facebook" href="'.htmlspecialchars($loginURL).'"><i class="fa fa-facebook" aria-hidden="true"></i></a>';
	}

?>