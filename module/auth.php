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
		
		//fetch auth_token record
		$tmp_credentials = $this->select_record_by_token_credentials($db, $token_credentials);
		$this->garbage_collect($db);
		
		//auth_token has already inserted?
		if ($tmp_credentials !== false) {
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $token_credentials);
	
			if ($result !== false) {
				//completed!
				//set auth_token to cookie
				$this->set_auth_token_to_cookie($auth_token);
				$message[] = "簡易ログイン用のトークンを更新しました。";
				$message[] = "このページをブックマークして次回からご利用ください。";
			} else {
				$message[] = "簡易ログイン用のトークンを更新できませんでした。";
			}
		} else {
			$auth_token = $this->generate_auth_token();
			$result = $this->insert_record($db, $auth_token, $token_credentials);
			
			if ($result !== false) {
				//completed!
				//set auth_token to cookie
				$this->set_auth_token_to_cookie($auth_token);
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
		
		//fetch auth_token record
		$auth_token = $this->get_auth_token_from_cookie();
		$tmp_credentials = $this->select_record_by_auth_token($db, $auth_token);
		
		//auth_token has already inserted?
		if ($tmp_credentials !== false) {
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $tmp_credentials);
	
			if ($result !== false) {
				$connection = new TwitterOAuth(
					$consumer_key, $consumer_secret,
					$tmp_credentials['oauth_token'],
					$tmp_credentials['oauth_token_secret']);
	
				$response = $connection->get('account/verify_credentials');
				
				//completed!
				//set auth_token to cookie and store token_credentials to session
				$token_credentials = array(
					'oauth_token' => $tmp_credentials['oauth_token'],
					'oauth_token_secret' => $tmp_credentials['oauth_token_secret'],
					'user_id' => $response->id_str,
					'screen_name' => $response->screen_name,
				);
				$this->set_auth_token_to_cookie($auth_token);
				$_SESSION['token_credentials'] = $token_credentials;

				//redirect
				header('Location: ' . $this->get_uri('top'));
				exit(1);
				
			} else {
				$message[] = "簡易ログインに失敗しました。";
			}
		} else {
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
