<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_auth extends Module_utilities
{
	//簡易ログインを設定
	function auth_set($token_credentials)
	{
		$message = array();
		$db = $this->initialize_database();
		
		//load record and delete unused records
		$tmp_credentials = $this->load_record_by_token_credentials($db, $token_credentials);
		$this->garbage_collect($db);
		
		//logged in
		if ($tmp_credentials !== false) {
			//record exists
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $token_credentials);
			
			//completed
			if ($result !== false) {
				$this->store_auth_token($auth_token);
				$message[] = "簡易ログイン用のトークンを更新しました。";
				$message[] = "このページをブックマークして次回からご利用ください。";
			} else {
				$message[] = "簡易ログイン用のトークンを更新できませんでした。";
			}
		} else {
			//record does not exist
			$auth_token = $this->generate_auth_token();
			$result = $this->store_record($db, $auth_token, $token_credentials);
			
			//completed
			if ($result !== false) {
				$this->store_auth_token($auth_token);
				$message[] = "簡易ログイン用のトークンを登録しました。";
				$message[] = "このページをブックマークして次回からご利用ください。";
			} else {
				$message[] = "簡易ログイン用のトークンを登録できませんでした。";
			}
		}
		
		$this->set_assign('message', $message);
	}
	
	//簡易ログイン
	function auth_get($consumer_key, $consumer_secret)
	{
		$message = array();
		$db = $this->initialize_database();
		
		//callback
		if ($this->post['callback'] != "") {
			$callback = $this->post['callback'];
		} else {
			$callback = $this->get_uri('top');
		}
		
		//load token_credentials
		$auth_token = $this->load_auth_token();
		$tmp_credentials = $this->load_record_by_auth_token($db, $auth_token);
		
		//not logged in
		if ($tmp_credentials !== false) {
			//record exists
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $tmp_credentials);
			
			//updated
			if ($result !== false) {
				//get instance of twitteroauth
				$connection = new TwitterOAuth(
					$consumer_key, $consumer_secret,
					$tmp_credentials['oauth_token'],
					$tmp_credentials['oauth_token_secret']);
				$connection->format = 'xml';
				
				//get authenticated user's information
				$response = $connection->get('account/verify_credentials');
				$xml = @simplexml_load_string($response);
				
				//store auth_token and token_credentials
				$token_credentials = array(
					'oauth_token' => $tmp_credentials['oauth_token'],
					'oauth_token_secret' => $tmp_credentials['oauth_token_secret'],
					'user_id' => (string)$xml->id,
					'screen_name' => (string)$xml->screen_name,
				);
				$this->store_auth_token($auth_token);
				$_SESSION['token_credentials'] = $token_credentials;
				
				header('Location: ' . $this->get_uri('top'));
				exit(1);
				
			} else {
				$message[] = "簡易ログインに失敗しました。";
			}
		} else {
			//record not exists
			$message[] = "簡易ログイン用のトークンが登録されていません。";
		}
		
		$this->set_assign('message', $message);
	}
	
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = $_SESSION['token_credentials'];
		
		//ログイン状態で操作を振り分け
		if ($token_credentials == "") {
			$this->auth_get($consumer_key, $consumer_secret);
		} else {
			$this->auth_set($token_credentials);
		}
		
		$this->render();
	}
}

?>
