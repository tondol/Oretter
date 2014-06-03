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
		);
		if ($this->request['max_id'] != "") {
			$params['max_id'] = $this->request['max_id'];
		}
		if ($this->request['since_id'] != "") {
			$params['since_id'] = $this->request['since_id'];
		}
		$_SESSION['callback'] = $this->get_uri(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get response
		$params = array(
			'lang' => 'ja',
			'q' => $query,
			'count' => 40,
		);
		if ($this->request['max_id'] != "") {
			$params['max_id'] = $this->request['max_id'];
		}
		if ($this->request['since_id'] != "") {
			$params['since_id'] = $this->request['since_id'];
		}
		$response = $connection->get('search/tweets', $params);
		$this->set_assign('entries', $response->statuses);

		//max_id, since_id
		if ($this->request['max_id']) {
			$this->set_assign('max_id', $this->request['max_id']);
		} else if (count($response->statuses) != 0) {
			$this->set_assign('max_id', $response->statuses[0]->id_str);
		}
		if ($this->request['since_id'] != "") {
			$this->set_assign('since_id', $this->request['since_id']);
		} else if (count($response->statuses) != 0) {
			$this->set_assign('since_id', $response->statuses[count($response->statuses) - 1]->id_str);
		}

		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		$this->render();
	}
}

?>
