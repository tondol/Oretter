<?php

require_once 'controller_oretter.php';

class Controller_lists extends Controller_oretter
{
	function run()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = array_at($_SESSION, 'token_credentials');
		
		//not logged in
		if ($token_credentials == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//get list-id and screen_name
		$id = array_at($this->get, 'id');
		$screen_name = array_at($this->get, 'screen_name');
		if ($id == "" || $screen_name == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		$this->set_assign('id', $id);
		$this->set_assign('screen_name', $screen_name);
		
		//pager
		if (!empty($this->get['p'])) {
			$current = max(intval($this->get['p']), 1);
		} else {
			$current = 1;
		}
		$this->set_assign('current', $current);
		$this->set_assign('next', $current + 1);
		$this->set_assign('prev', $current - 1);
		
		//callback
		$params = array(
			'id' => $id,
			'screen_name' => $screen_name,
			'p' => $current,
		);
		$_SESSION['callback'] = $this->get_uri(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get list
		$lists = $connection->get('lists/show', array('list_id' => $id));
		$this->set_assign('lists', $lists);
		
		//get statuses
		$response = $connection->get(
			'lists/statuses',
			array(
				'list_id' => $id,
				'per_page' => 40,
				'page' => $current,
			)
		);
		$this->set_assign('statuses', $response);
		
		//overwrite current name
		$current = $this->get_current();
		$this->config['pages'][$current] = escape($lists->name);
		$this->render();
	}
}
