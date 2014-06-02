<?php

require_once 'twitteroauth.php';

class Module_post_retweet extends Module
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
		
		if ($this->post['id'] == "") {
			//id is not supplied
			$message = "RTするステータスを指定してください。";
			
		} else if (
			$this->post['post_token'] == "" ||
			$this->post['post_token'] != $_SESSION['post_token'])
		{
			//duplicated post
			$message = "もう一度やり直してください。";
			
		} else {
			//get instance of twitteroauth
			$connection = new TwitterOAuth(
				$consumer_key, $consumer_secret,
				$token_credentials['oauth_token'],
				$token_credentials['oauth_token_secret']);
			
			//get response
			$response = $connection->post('statuses/retweet/' . $this->post['id']);
			
			//check response
			if ($response->id_str != "") {
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
