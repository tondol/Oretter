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
		if ($_POST['callback'] != "") {
			$callback = $_POST['callback'];
		} else {
			$callback = $this->get_uri('top');
		}
		
		//load token_credentials
		$data = $this->load_token_credentials($db, $_COOKIE['auth_token']);
		
		//not logged in
		if ($data !== false) {
			//auth_token supplied
			$_SESSION['token_credentials'] = $data;
			$result = $this->regenerate_auth_token($db, $data);
			//completed
			if ($result !== false) {
				$this->store_auth_token($result);
				$message = "簡易ログインが完了しました。";
			} else {
				$message = "簡易ログインに失敗しました。";
			}
		} else {
			//auth_token not supplied
			$this->store_auth_token('');
			$message = "簡易ログイン用のトークンが登録されていません。";
		}
		
		$this->set_assign('message', $message);
		$this->render();
	}
}

?>
