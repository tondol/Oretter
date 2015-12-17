<?php

require_once 'controller_oretter.php';

class Controller_login extends Controller_oretter
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
			$this->set_auth_token_to_cookie($auth_token);
			$result = $this->update_record($db, $auth_token, $token_credentials);
			//completed!
		} else {
			$auth_token = $this->generate_auth_token();
			$this->set_auth_token_to_cookie($auth_token);
			$result = $this->insert_record($db, $auth_token, $token_credentials);
			//completed!
		}
	}

	function run()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];

		//callback from twitter
		if (!empty($this->get['verifying'])) {
			if (!empty($this->get['oauth_verifier'])) {
				$connection = new TwitterOAuth(
					$consumer_key, $consumer_secret,
					$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

				$token_credentials = $connection->getAccessToken($this->get['oauth_verifier']);

				if (!empty($token_credentials['oauth_token'])) {
					//completed!
					//set token_credentials to session
					session_regenerate_id();
					$this->set_auth($token_credentials);
					$_SESSION['token_credentials'] = $token_credentials;
					$_SESSION['post_token'] = sha1(session_id());
					header('Location: ' . $this->get_url('top'));
					
				} else {
					$message = "ログインに失敗しました。";
					$this->set('message', $message);
					$this->render();
				}
				
			} else {
				$message = "ログインに失敗しました。";
				$this->set('message', $message);
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
		$redirect_url = $this->get_url(null, array('verifying' => true));
		$temporary_credentials = $connection->getRequestToken($redirect_url);
		
		//store temporary_credentials
		$_SESSION['oauth_token'] = $temporary_credentials['oauth_token'];
		$_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];
		$oauth_url = $connection->getAuthorizeURL($temporary_credentials);

		header('Location: ' . $oauth_url);
	}
}
