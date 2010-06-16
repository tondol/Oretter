<?php

require_once 'twitteroauth.php';

class Module_post_tweet extends Module
{
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = $_SESSION['token_credentials'];
		
		//not logged in
		if ($token_credentials == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//callback
		if ($_POST['callback'] != "") {
			$callback = $_POST['callback'];
		} else {
			$callback = $this->get_uri('top');
		}
		
		if ($_POST['status'] == "") {
			//status is not supplied
			$message = "ステータスを入力してください。";
			
		} else if (
			$_POST['post_token'] == "" ||
			$_POST['post_token'] != $_SESSION['post_token'])
		{
			//duplicated post
			$message = "もう一度やり直してください。";
			
		} else {
			//get instance of twitteroauth
			$connection = new TwitterOAuth(
				$consumer_key, $consumer_secret,
				$token_credentials['oauth_token'],
				$token_credentials['oauth_token_secret']);
			$connection->format = 'xml';
			
			//make parameters
			$params = array();
			$params['status'] = $_POST['status'];
			if ($_POST['in_reply_to_status_id'] != "") {
				$params['in_reply_to_status_id'] = $_POST['in_reply_to_status_id'];
			}
			
			//get response
			$response = $connection->post('statuses/update', $params);
			$xml = simplexml_load_string($response);
			
			//check response
			if ($xml->id != "") {
				$message = "ステータスは正しく更新されました。";
			} else {
				$message = "おやおや、何かおかしい！";
			}
		}
		
		//delete token
		unset($_SESSION['post_token']);
		
		$this->set_assign('message', $message);
		$this->set_assign('callback', $callback);
		$this->render();
	}
}

?>
