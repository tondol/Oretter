<?php

require_once 'controller_oretter.php';

class Controller_search extends Controller_oretter
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
		
		//get search_query
		//store search_query
		$query = array_at($this->get, 'q');
		if ($query != "") {
			$_SESSION['search_query'] = $query;
		} else {
			$query = array_at($_SESSION, 'search_query');
		}
		$this->set('query', $query);
	
		//callback
		$params = array(
			'q' => $query,
		);
		if (!empty($this->get['max_id'])) {
			$params['max_id'] = $this->get['max_id'];
		}
		if (!empty($this->get['since_id'])) {
			$params['since_id'] = $this->get['since_id'];
		}
		$_SESSION['callback'] = $this->get_url(null, $params);
		
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
		if (!empty($this->get['max_id'])) {
			$params['max_id'] = $this->get['max_id'];
		}
		if (!empty($this->get['since_id'])) {
			$params['since_id'] = $this->get['since_id'];
		}
		$response = $connection->get('search/tweets', $params);
		$this->set('entries', array_at($response, 'statuses'));

		//max_id, since_id
		if (!empty($this->get['max_id'])) {
			$this->set('max_id', $this->get['max_id']);
		} else if (!empty($response->statuses)) {
			$this->set('max_id', $response->statuses[0]->id_str);
		}
		if (!empty($this->get['since_id'])) {
			$this->set('since_id', $this->get['since_id']);
		} else if (!empty($response->statuses)) {
			$this->set('since_id', $response->statuses[count($response->statuses) - 1]->id_str);
		}

		$this->render();
	}
}
