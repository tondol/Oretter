<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_search extends Module_utilities
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
		if ($this->request['p'] != "") {
			$current = max(intval($this->request['p']), 1);
		} else {
			$current = 1;
		}
		$this->set_assign('current', $current);
		$this->set_assign('next', $current + 1);
		$this->set_assign('prev', $current - 1);
		
		//get search_query
		//store search_query
		$query = $this->request['q'];
		if ($query != "") {
			$_SESSION['search_query'] = $query;
		} else {
			$query = $_SESSION['search_query'];
		}
		$this->set_assign('query', $query);
		
		//callback
		$params = array(
			'q' => $query,
			'p' => $current,
		);
		$_SESSION['callback'] = $this->get_uri(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get response
		$response = $connection->get(
			'search/tweets',
			array(
				'lang' => 'ja',
				'q' => $query,
				/* 'page' => $current, */
				'rpp' => 40,
			));
		$this->set_assign('entries', $response->statuses);
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		$this->render();
	}
}

?>
