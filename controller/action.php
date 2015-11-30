<?php

require_once 'controller_oretter.php';

class Controller_action extends Controller_oretter
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
		
		//id is not suplied
		if (empty($this->get['id'])) {
			header('Location: ' . $callback);
			exit(1);
		}
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get status
		$response = $connection->get(
			'statuses/show',
			array('id' => $this->get['id']));
		$this->set('status', $response);
		
		//get reply
		if (!empty($response->in_reply_to_status_id_str)) {
			$response = $connection->get(
				'statuses/show',
				array('id' => $response->in_reply_to_status_id_str));
			$this->set('reply', $response);
		}
		
		$this->render();
	}
}
