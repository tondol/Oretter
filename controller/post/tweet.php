<?php

class Controller_post_tweet extends Controller
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
		
		if (empty($this->post['status'])) {
			//status is not supplied
			$message = "ステータスを入力してください。";
			
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
			
			//make parameters
			$params = array(
				'status' => $this->post['status'],
			);
			if (!empty($this->post['in_reply_to_status_id'])) {
				$params['in_reply_to_status_id'] = $this->post['in_reply_to_status_id'];
			}
			
			//get response
			$response = $connection->post('statuses/update', $params);

			//check response
			if ($response->id_str != "") {
				$message = "ステータスは正しく更新されました。";
			} else {
				$message = "おやおや、何かおかしい！";
			}
		}
		
		$this->set('message', $message);
		$this->render();
	}
}
