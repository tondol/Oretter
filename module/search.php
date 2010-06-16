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
		if ($_GET['p'] != "") {
			$current = max(intval($_GET['p']), 1);
		} else {
			$current = 1;
		}
		$this->set_assign('current', $current);
		$this->set_assign('next', $current + 1);
		$this->set_assign('prev', $current - 1);
		
		//get search_query
		//store search_query
		$query = $_GET['q'];
		if ($query != "") {
			$_SESSION['search_query'] = $query;
		} else {
			$query = $_SESSION['search_query'];
		}
		$this->set_assign('query', $query);
		
		//callback
		$params = http_build_query(array(
			'page' => $this->get_current(),
			'q' => $query,
			'p' => $current,
		));
		$callback = $this->get_uri('top') . '?' . $params;
		$this->set_assign('callback', $callback);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get response
		$uri = 'http://search.twitter.com/search.atom';
		$params = http_build_query(array(
			'lang' => 'ja',
			'q' => $query,
			'page' => $current,
			'rpp' => 40,
		));
		$response = file_get_contents($uri . '?' . $params);
		$xml = simplexml_load_string($response);
		$this->set_assign('entry', $xml->entry);
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		$this->render();
	}
}

?>
