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
		
		//load token_credentials
		$data = $this->load_auth_token($db, $token_credentials);
		
		//delete unused token_credential records
		$this->garbage_collect($db);
		
		//logged in
		if ($data !== false) {
			//auth_token supplied
			$result = $this->regenerate_auth_token($db, $token_credentials);
			//completed
			if ($result !== false) {
				$this->store_auth_token($result);
				$message = "簡易ログイン用のトークンを更新しました。";
			} else {
				$message = "簡易ログイン用のトークンを更新できませんでした。";
			}
		} else {
			//auth_token not supplied
			$result = $this->store_token_credentials($db, $token_credentials);
			//completed
			if ($result !== false) {
				$this->store_auth_token($result);
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
