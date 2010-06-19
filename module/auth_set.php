<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_auth_set extends Module_utilities
{
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = $_SESSION['token_credentials'];
		$db = $this->initialize_database();
		
		//not logged in
		if ($token_credentials == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//load record and delete unused records
		$data = $this->load_record_by_token_credentials($db, $token_credentials);
		$this->garbage_collect($db);
		
		//logged in
		if ($data !== false) {
			//record exists
			$auth_token = $this->generate_auth_token();
			$result = $this->update_record($db, $auth_token, $token_credentials);
			
			//completed
			if ($result !== false) {
				$this->store_auth_token($auth_token);
				$message = "簡易ログイン用のトークンを更新しました。";
			} else {
				$message = "簡易ログイン用のトークンを更新できませんでした。";
			}
		} else {
			//record does not exist
			$auth_token = $this->generate_auth_token();
			$result = $this->store_record($db, $auth_token, $token_credentials);
			
			//completed
			if ($result !== false) {
				$this->store_auth_token($auth_token);
				$message = "簡易ログイン用のトークンを登録しました。";
			} else {
				$message = "簡易ログイン用のトークンを登録できませんでした。";
			}
		}
		
		$this->set_assign('message', $message);
		$this->render();
	}
}

?>
