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
		if ($this->request['verifying'])
		{
			if ($this->request['oauth_verifier'] != "") {
				//get instance of twitteroauth
				$connection = new TwitterOAuth(
					$consumer_key, $consumer_secret,
					$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
				
				//get access token
				$token_credentials = $connection->getAccessToken($this->request['oauth_verifier']);
				
				//token_credentials is supplied
				if ($token_credentials['oauth_token'] != "") {
					//succeeded to login with oauth
					session_regenerate_id();
					$_SESSION['token_credentials'] = $token_credentials;
					header('Location: ' . $this->get_uri('top'));
					
				} else {
					//failed to login with oauth
					$message = "ログインに失敗しました。";
					$this->set_assign('message', $message);
					$this->render();
				}
				
			} else {
				//failed to login with oauth
				$message = "ログインに失敗しました。";
				$this->set_assign('message', $message);
				$this->render();
			}
			
			//destroy temporary_credentials
			unset($_SESSION['oauth_token']);
			unset($_SESSION['oauth_token_secret']);
			exit(1);
		}
		
		//get temporary_credentials
		$connection = new TwitterOAuth($consumer_key, $consumer_secret);
		$redirect_uri = $this->get_uri(null, array('verifying' => true));
		$temporary_credentials = $connection->getRequestToken($redirect_uri);
		
		//store temporary_credentials
		$_SESSION['oauth_token'] = $temporary_credentials['oauth_token'];
		$_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];
		$oauth_uri = $connection->getAuthorizeURL($temporary_credentials);
		
		header('Location: ' . $oauth_uri);
	}
}

?>
