<?php

require_once 'controller_oretter.php';

class Controller_post_follow extends Controller_oretter
{
	function run()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = array_at($_SESSION, 'token_credentials');
		
		//not logged in
		if ($token_credentials == "") {
			header('Location: ' . $this->get_url('top'));
			exit(1);
		}
		
		if (empty($this->post['id'])) {
			//id is not supplied
			$message = "フォローするユーザーを指定してください。";
			
		} else if (empty($this->post['post_token']) ||
				empty($_SESSION['post_token']) ||
				$this->post['post_token'] != $_SESSION['post_token']) {
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
		
		$this->set('message', $message);
		$this->render();
	}
}
