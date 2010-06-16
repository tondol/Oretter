<?php

require_once "twitteroauth.php";
require_once dirname(__FILE__) . '/utilities.php';

class Module_login extends Module_utilities
{
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		
		//callback from twitter
		if ($_SESSION['oauth_token'] != "")
		{
			//get instance of twitteroauth
			$connection = new TwitterOAuth(
				$consumer_key, $consumer_secret,
				$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
			
			//get access token
			$token_credentials = $connection->getAccessToken($_GET['oauth_verifier']);
			
			//token_credentials is supplied
			if ($token_credentials['oauth_token'] != "") {
				//store token_credentials
				session_regenerate_id();
				$_SESSION['token_credentials'] = $token_credentials;
				//destroy temporary_credentials
				unset($_SESSION['oauth_token']);
				unset($_SESSION['oauth_token_secret']);
				header('Location: ' . $this->get_uri('top'));
				exit(1);
			}
		}
		
		//get temporary_credentials
		$connection = new TwitterOAuth($consumer_key, $consumer_secret);
		$temporary_credentials = $connection->getRequestToken($this->get_uri());
		
		//store temporary_credentials
		$_SESSION['oauth_token'] = $temporary_credentials['oauth_token'];
		$_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];
		$redirect_uri = $connection->getAuthorizeURL($temporary_credentials);
		
		header('Location: ' . $redirect_uri);
		exit(1);
	}
}

?>
