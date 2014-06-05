<?php

require_once 'twitteroauth.php';
require_once dirname(dirname(__FILE__)) . '/utilities.php';

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
			
			//get response
			$response = $connection->post('friendships/create', array('id' => $this->post['id']));
			
			//check response
			if ($response->id_str != "") {
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
