<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_user extends Module_utilities
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
		
		//get screen_name
		$screen_name = $this->request['screen_name'];
		if ($screen_name == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//pager
		if ($this->request['p'] != "") {
			$current = max(intval($this->request['p']), 1);
		} else {
			$current = 1;
		}
		$this->set_assign('current', $current);
		$this->set_assign('next', $current + 1);
		$this->set_assign('prev', $current - 1);
		
		//callback
		$params = array(
			'screen_name' => $screen_name,
			'p' => $current,
		);
		$_SESSION['callback'] = $this->get_uri(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		$connection->format = 'xml';
		
		//get user
		$response = $connection->get(
			'users/show',
			array(
				'screen_name' => $screen_name,
			));
		$xml = simplexml_load_string($response);
		$this->set_assign('user', $xml);
		
		//get friendship
		$response = $connection->get(
			'friendships/show',
			array(
				'target_screen_name' => $screen_name,
			));
		$xml = @simplexml_load_string($response);
		$this->set_assign('target', $xml->target);
		
		//get statuses
		$response = $connection->get(
			'statuses/user_timeline',
			array(
				'screen_name' => $screen_name,
				'page' => $current,
				'count' => 40,
				'include_rts' => true,
			));
		$xml = simplexml_load_string($response);
		$this->set_assign('statuses', $xml->status);
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		//overwrite current name
		$current = $this->get_current();
		$this->config['pages'][$current] = escape($screen_name);
		$this->render();
	}
}

?>
