<?php

require_once 'twitteroauth.php';
require_once dirname(dirname(__FILE__)) . '/utilities.php';

class Module_info_lists extends Module_utilities
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
		
		//screen_name
		$screen_name = array_at($this->request, 'screen_name');
		if ($screen_name == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//cursor
		$cursor = array_at($this->request, 'cursor');
		if ($cursor == "") {
			$cursor = -1;
		}
		$this->set_assign('current', $cursor);
		
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
		$this->set_assign('user', $user);
		
		//get lists
		$response = $this->get_and_parse($connection, $user, $cursor);
		$this->set_assign('lists', $response);
		$this->set_assign('next', (string)$this->get_next_cursor());
		$this->set_assign('prev', (string)$this->get_prev_cursor());
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		//overwrite page names
		$this->config['pages']['info/lists'] = "{$user->screen_name}が作成したリスト・購読しているリスト";
		$this->config['pages']['info/lists_ownerships'] = "{$user->screen_name}が作成したリスト";
		$this->config['pages']['info/lists_subscriptions'] = "{$user->screen_name}が購読しているリスト";
		$this->config['pages']['info/lists_memberships'] = "{$user->screen_name}が登録されているリスト";
		
		$this->render();
	}
}
