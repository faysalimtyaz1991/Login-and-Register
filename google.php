<?php

	//Include Google client library 
		include_once 'src/gplus/vendor/autoload.php';
		
		/*
		 * Configuration and setup Google API
		 */
		$client_id = '304788242170-8buhe5n5k46dsdoeujmh1hgr7lgio8f2.apps.googleusercontent.com';
		$client_secret = 'N6SjNkr_I7I1rVl93cBRJOlG';
		$redirect_url = 'http://localhost/auth/login.php';
		/* 
		 * INITIALIZATION
		 *
		 * Create a google client object
		 * set the id,secret and redirect uri
		 * set the scope variables if required
		 * create google plus object
		 */
		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_url);
		$client->setScopes('email');
		$plus = new Google_Service_Plus($client);
		/*
		 * PROCESS
		 *
		 * A. Pre-check for logout
		 * B. Authentication and Access token
		 * C. Retrive Data
		 */
		/* 
		 * A. PRE-CHECK FOR LOGOUT
		 * 
		 * Unset the session variable in order to logout if already logged in    
		 */
		if (isset($_REQUEST['logout'])) {
		   session_unset();
		}
		/* 
		 * B. AUTHORIZATION AND ACCESS TOKEN
		 *
		 * If the request is a return url from the google server then
		 *  1. authenticate code
		 *  2. get the access token and store in session
		 *  3. redirect to same url to eleminate the url varaibles sent by google
		 */
		if (isset($_GET['code'])) {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}
		/* 
		 * C. RETRIVE DATA
		 * 
		 * If access token if available in session 
		 * load it to the client object and access the required profile data
		 */
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  $client->setAccessToken($_SESSION['access_token']);
		  $me = $plus->people->get('me');
		  // Get User data
		  $id = $me['id'];
		  $name =  $me['displayName'];
		  $email =  $me['emails'][0]['value'];
		  $profile_image_url = $me['image']['url'];
		  $cover_image_url = $me['cover']['coverPhoto']['url'];
		  $profile_url = $me['url'];

		  $_SESSION['user'] = $id;
		  $_SESSION['name'] = $name;

		} else {
		  // get the login url   
		  $authUrl = $client->createAuthUrl();
		}
		
	    if (isset($authUrl)) {
	        $output .= '<a class="btn btn-primary social-btn google" href="'.$authUrl.'"><i class="fa fa-google-plus" aria-hidden="true"></i></a>';
	    }

?>