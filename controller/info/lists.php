<?php

require_once 'controller_oretter.php';

class Controller_info_lists extends Controller_oretter
{
	//sub classes have to extend this method
	function get_and_parse($connection, $user, $cursor)
	{
		$this->response = $connection->get(
			'lists/list',
			array(
				'screen_name' => $user->screen_name,
				'cursor' => $cursor,
			));
		return $this->response;
	}
	function get_next_cursor()
	{
		return null;
	}
	function get_prev_cursor()
	{
		return null;
	}
	
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
		
		//screen_name
		$screen_name = array_at($this->get, 'screen_name');
		if ($screen_name == "") {
			header('Location: ' . $this->get_url('top'));
			exit(1);
		}
		
		//cursor
		$cursor = array_at($this->get, 'cursor');
		if ($cursor == "") {
			$cursor = -1;
		}
		$this->set('current', $cursor);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get user
		$user = $connection->get(
			'users/show',
			array('screen_name' => $screen_name)
		);
		$this->set('user', $user);
		
		//get lists
		$response = $this->get_and_parse($connection, $user, $cursor);
		$this->set('lists', $response);
		$this->set('next', (string)$this->get_next_cursor());
		$this->set('prev', (string)$this->get_prev_cursor());
		
		//overwrite page names
		$this->config['chain']['info/lists'] = "{$user->screen_name}が作成したリスト・購読しているリスト";
		$this->config['chain']['info/lists_ownerships'] = "{$user->screen_name}が作成したリスト";
		$this->config['chain']['info/lists_subscriptions'] = "{$user->screen_name}が購読しているリスト";
		$this->config['chain']['info/lists_memberships'] = "{$user->screen_name}が登録されているリスト";
		
		$this->render();
	}
}
