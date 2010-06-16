<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_mentions extends Module_utilities
{
	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = $_SESSION['token_credentials'];
		
		if ($token_credentials == "") {
			header('Location: ' . $this->get_uri('top'));
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
			'page' => $this->get_current(),
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
			'statuses/mentions',
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
