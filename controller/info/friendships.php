<?php

require 'controller_oretter.php';

class Controller_info_friendships extends Controller_oretter
{
	//sub classes have to extend this method
	function get_and_parse($connection, $user, $cursor)
	{
		return null;
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
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//screen_name
		$screen_name = array_at($this->get, 'screen_name');
		if ($screen_name == "") {
			header('Location: ' . $this->get_uri('top'));
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
		
		//get ids
		$response = $this->get_and_parse($connection, $user, $cursor);
		$this->set('next', $this->get_next_cursor());
		$this->set('prev', $this->get_prev_cursor());
		$this->set('friends', $response);
		
		//overwrite page names
		$this->config['chain']['info/friendships_friends'] = "{$user->screen_name}がフォローしているユーザー";
		$this->config['chain']['info/friendships_followers'] = "{$user->screen_name}をフォローしているユーザー";
		
		$this->render();
	}
}
