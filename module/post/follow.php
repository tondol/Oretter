<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_post_follow extends Module_utilities
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
			$message = "フォローするユーザーを指定してください。";
			
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
			$connection->format = 'xml';
			
			//get response
			$response = $connection->post('friendships/create/' . $this->post['id']);
			$xml = @simplexml_load_string($response);
			
			//check response
			if ($xml->id != "") {
				$message = "フォローが正しく完了しました。";
			} else {
				$message = "おやおや、何かおかしい！";
			}
		}
		
		//delete token
		unset($_SESSION['post_token']);
		
		$this->set_assign('message', $message);
		$this->render();
	}
}

?>
