<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_top extends Module_utilities
{
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = $_SESSION['token_credentials'];
		
		//not logged in
		if ($token_credentials == "") {
			$this->render();
			exit(1);
		}
		
		//pager
		if ($_GET['p'] != "") {
			$current = max(intval($_GET['p']), 1);
		} else {
			$current = 1;
		}
		$this->set_assign('current', $current);
		$this->set_assign('next', $current + 1);
		$this->set_assign('prev', $current - 1);
		
		//callback
		$params = http_build_query(array(
			'p' => $current,
		));
		$callback = $this->get_uri('top') . '?' . $params;
		$this->set_assign('callback', $callback);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		$connection->format = 'xml';
		
		//get response
		$response = $connection->get(
			'statuses/home_timeline',
			array(
				'count' => 40,
				'page' => $current,
			));
		$xml = simplexml_load_string($response);
		$this->set_assign('status', $xml->status);
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		$this->render();
	}
}

?>
