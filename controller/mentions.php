<?php

require_once 'controller_oretter.php';

class Controller_mentions extends Controller_oretter
{
	function run()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = array_at($_SESSION, 'token_credentials');
		
		if ($token_credentials == "") {
			header('Location: ' . $this->get_url('top'));
			exit(1);
		}
		
		//pager
		if (!empty($this->get['p'])) {
			$current = max(intval($this->get['p']), 1);
		} else {
			$current = 1;
		}
		$this->set('current', $current);
		$this->set('next', $current + 1);
		$this->set('prev', $current - 1);
		
		//callback
		$params = array(
			'p' => $current,
		);
		$_SESSION['callback'] = $this->get_url(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get self
		$response = $connection->get('account/verify_credentials');
		$this->set('self', $response);
		
		//get response
		$response = $connection->get(
			'statuses/mentions_timeline',
			array(
				'count' => 40,
				'page' => $current,
			));
		$this->set('statuses', $response);
		
		$this->render();
	}
}
