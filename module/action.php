<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_action extends Module_utilities
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
		
		//id is not suplied
		if (empty($this->request['id'])) {
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
			array('id' => $this->request['id']));
		$this->set_assign('status', $response);
		
		//get reply
		if (!empty($response->in_reply_to_status_id_str)) {
			$response = $connection->get(
				'statuses/show',
				array('id' => $response->in_reply_to_status_id_str));
			$this->set_assign('reply', $response);
		}
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		$this->render();
	}
}
