<?php

require_once 'twitteroauth.php';
require_once dirname(dirname(__FILE__)) . '/utilities.php';

class Module_info_lists extends Module_utilities
{
	//sub classes have to extend this method
	function get_and_parse($connection, $user, $cursor)
	{
		$response = $connection->get(
			(string)$user->screen_name . '/lists',
			array('cursor' => $cursor)
		);
		return @simplexml_load_string($response);
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
		$screen_name = $this->request['screen_name'];
		if ($screen_name == "") {
			header('Location: ' . $this->get_uri('top'));
			exit(1);
		}
		
		//cursor
		$cursor = $this->request['cursor'];
		if ($cursor == "") {
			$cursor = -1;
		}
		$this->set_assign('current', $cursor);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		$connection->format = 'xml';
		
		//get user
		$response = $connection->get(
			'users/show',
			array('screen_name' => $screen_name)
		);
		$user = @simplexml_load_string($response);
		$this->set_assign('user', $user);
		
		//get lists
		$xml = $this->get_and_parse($connection, $user, $cursor);
		$this->set_assign('lists', $xml->lists->list);
		$this->set_assign('next', (string)$xml->next_cursor);
		$this->set_assign('prev', (string)$xml->previous_cursor);
		
		//token
		$post_token = guid();
		$_SESSION['post_token'] = $post_token;
		$this->set_assign('post_token', $post_token);
		
		//overwrite page names
		$this->config['pages']['info/lists'] = "{$user->screen_name}の作成したリスト";
		$this->config['pages']['info/lists_memberships'] = "{$user->screen_name}をフォローしているリスト";
		$this->config['pages']['info/lists_subscriptions'] = "{$user->screen_name}がフォローしているリスト";
		
		$this->render();
	}
}

?>
