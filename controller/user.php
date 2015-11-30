<?php

require_once 'controller_oretter.php';

class Controller_user extends Controller_oretter
{
	function run()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = array_at($_SESSION, 'token_credentials');
		
		//not logged in
		if ($token_credentials == "") {
			header('Location: ' . $this->get_url('top'));
			exit(1);
		}
		
		//get screen_name
		$screen_name = array_at($this->get, 'screen_name');
		if ($screen_name == "") {
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
			'screen_name' => $screen_name,
			'p' => $current,
		);
		$_SESSION['callback'] = $this->get_url(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get user
		$response = $connection->get(
			'users/show',
			array(
				'screen_name' => $screen_name,
			));
		$this->set('user', $response);
		
		//get friendship
		$response = $connection->get(
			'friendships/show',
			array(
				'target_screen_name' => $screen_name,
			));
		$this->set('source', $response->relationship->source);
		
		//get statuses
		$response = $connection->get(
			'statuses/user_timeline',
			array(
				'screen_name' => $screen_name,
				'page' => $current,
				'count' => 40,
				'include_rts' => true,
			));
		$this->set('statuses', $response);
		
		//overwrite current name
		$current = $this->get_chain();
		$this->config['chain'][$current] = h($screen_name);
		$this->render();
	}
}
