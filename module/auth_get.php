<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_auth_get extends Module_utilities
{
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = $_SESSION['token_credentials'];
		$db = $this->initialize_database();
		
		//logged in
		if ($token_credentials != "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//callback
		if ($this->post['callback'] != "") {
			$callback = $this->post['callback'];
		} else {
			$callback = $this->get_uri('top');
		}
		
		//load token_credentials
		$auth_token = $this->load_auth_token();
		$data = $this->load_record_by_auth_token($db, $auth_token);
		
		//not logged in
		if ($data !== false) {
			//record exists
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $data);
			
			//updated
			if ($result !== false) {
				//get instance of twitteroauth
				$connection = new TwitterOAuth(
					$consumer_key, $consumer_secret,
					$data['oauth_token'],
					$data['oauth_token_secret']);
				$connection->format = 'xml';
				
				//get authenticated user's information
				$response = $connection->get('account/verify_credentials');
				$xml = @simplexml_load_string($response);
				
				//store auth_token and token_credentials
				$token_credentials = array(
					'oauth_token' => $data['oauth_token'],
					'oauth_token_secret' => $data['oauth_token_secret'],
					'user_id' => (string)$xml->id,
					'screen_name' => (string)$xml->screen_name,
				);
				$this->store_auth_token($auth_token);
				$_SESSION['token_credentials'] = $token_credentials;
				
				header('Location: ' . $this->get_uri('top'));
				exit(1);
				
			} else {
				$message = "簡易ログインに失敗しました。";
			}
		} else {
			//record not exists
			$message = "簡易ログイン用のトークンが登録されていません。";
		}
		
		$this->set_assign('message', $message);
		$this->render();
	}
}

?>
