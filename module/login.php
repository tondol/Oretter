<?php

require_once "twitteroauth.php";
require_once dirname(__FILE__) . '/utilities.php';

class Module_login extends Module_utilities
{
	//簡易ログインを設定
	function set_auth($token_credentials)
	{
		$db = $this->initialize_database();
		
		//fetch auth_token record
		$tmp_credentials = $this->select_record_by_token_credentials($db, $token_credentials);
		$this->garbage_collect($db);
		
		//auth_token has already inserted?
		if ($tmp_credentials !== false) {
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $token_credentials);
	
			if ($result !== false) {
				//completed!
				$this->set_auth_token_to_cookie($auth_token);
			}
		} else {
			$auth_token = $this->generate_auth_token();
			$result = $this->insert_record($db, $auth_token, $token_credentials);
			
			if ($result !== false) {
				//completed!
				$this->set_auth_token_to_cookie($auth_token);
			}
		}
	}

	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		
		//callback from twitter
		if (!empty($this->request['verifying'])) {
			if (!empty($this->request['oauth_verifier'])) {
				$connection = new TwitterOAuth(
					$consumer_key, $consumer_secret,
					$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
				
				$token_credentials = $connection->getAccessToken($this->request['oauth_verifier']);
				
				if (!empty($token_credentials['oauth_token'])) {
					//completed!
					//set token_credentials to session
					session_regenerate_id();
					$this->set_auth($token_credentials);
					$_SESSION['token_credentials'] = $token_credentials;
					$_SESSION['post_token'] = sha1(session_id());
					header('Location: ' . $this->get_uri('top'));
					
				} else {
					$message = "ログインに失敗しました。";
					$this->set_assign('message', $message);
					$this->render();
				}
				
			} else {
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
		$connection->host = "https://api.twitter.com/1.1/";
		$redirect_uri = $this->get_uri(null, array('verifying' => true));
		$temporary_credentials = $connection->getRequestToken($redirect_uri);
		
		//store temporary_credentials
		$_SESSION['oauth_token'] = $temporary_credentials['oauth_token'];
		$_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];
		$oauth_uri = $connection->getAuthorizeURL($temporary_credentials);

		header('Location: ' . $oauth_uri);
	}
}
