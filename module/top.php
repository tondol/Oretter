<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/utilities.php';

class Module_top extends Module_utilities
{
	//簡易ログイン
	function get_auth($consumer_key, $consumer_secret)
	{
		$db = $this->initialize_database();
		
		//fetch auth_token record
		$auth_token = $this->get_auth_token_from_cookie();
		$tmp_credentials = $this->select_record_by_auth_token($db, $auth_token);
		
		//auth_token has already inserted?
		if ($tmp_credentials !== false) {
			$auth_token = $this->generate_auth_token();
			$this->set_auth_token_to_cookie($auth_token);
			$result = $this->update_record($db, $auth_token, $tmp_credentials);
	
			if ($result !== false) {
				//completed!
				return array(
					'oauth_token' => $tmp_credentials['oauth_token'],
					'oauth_token_secret' => $tmp_credentials['oauth_token_secret'],
				);
			}
		}

		return null;
	}

	function action()
	{
		session_start();
		
		$consumer_key = $this->config['twitter']['consumer_key'];
		$consumer_secret = $this->config['twitter']['consumer_secret'];
		$token_credentials = array_at($_SESSION, 'token_credentials');

		//not logged in
		if (empty($token_credentials)) {
			$token_credentials = $this->get_auth($consumer_key, $consumer_secret);
			if (!empty($token_credentials)) {
				//complete!
				//set auth_token to cookie and store token_credentials to session
				session_regenerate_id();
				$_SESSION['token_credentials'] = $token_credentials;
				$_SESSION['post_token'] = sha1(session_id());
			} else {
				$this->render();
				exit(1);
			}
		}
		
		//pager
		if (!empty($this->request['p'])) {
			$current = max(intval($this->request['p']), 1);
		} else {
			$current = 1;
		}
		$this->set_assign('current', $current);
		$this->set_assign('next', $current + 1);
		$this->set_assign('prev', $current - 1);
		
		//callback
		$params = array(
			'p' => $current,
		);
		$_SESSION['callback'] = $this->get_uri(null, $params);
		
		//get instance of twitteroauth
		$connection = new TwitterOAuth(
			$consumer_key, $consumer_secret,
			$token_credentials['oauth_token'],
			$token_credentials['oauth_token_secret']);
		
		//get response
		$response = $connection->get(
			'statuses/home_timeline',
			array(
				'count' => 40,
				'page' => $current,
			));
		$this->set_assign('statuses', $response);
	
		$this->render();
	}
}
