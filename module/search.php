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
		$token_credentials = array_at($_SESSION, 'token_credentials');
		
		if ($token_credentials == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//get search_query
		//store search_query
		$query = array_at($this->request, 'q');
		if ($query != "") {
			$_SESSION['search_query'] = $query;
		} else {
			$query = array_at($_SESSION, 'search_query');
		}
		$this->set_assign('query', $query);
	
		//callback
		$params = array(
			'q' => $query,
		);
		if (!empty($this->request['max_id'])) {
			$params['max_id'] = $this->request['max_id'];
		}
		if (!empty($this->request['since_id'])) {
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
		if (!empty($this->request['max_id'])) {
			$params['max_id'] = $this->request['max_id'];
		}
		if (!empty($this->request['since_id'])) {
			$params['since_id'] = $this->request['since_id'];
		}
		$response = $connection->get('search/tweets', $params);
		$this->set_assign('entries', array_at($response, 'statuses'));

		//max_id, since_id
		if (!empty($this->request['max_id'])) {
			$this->set_assign('max_id', $this->request['max_id']);
		} else if (!empty($response->statuses)) {
			$this->set_assign('max_id', $response->statuses[0]->id_str);
		}
		if (!empty($this->request['since_id'])) {
			$this->set_assign('since_id', $this->request['since_id']);
		} else if (!empty($response->statuses)) {
			$this->set_assign('since_id', $response->statuses[count($response->statuses) - 1]->id_str);
		}

		$this->render();
	}
}
