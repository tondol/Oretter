<?php

class Module_utilities extends Module
{
	//initialize php database object
	function initialize_database()
	{
		$dsn = "mysql:dbname={$this->config['db']['dbname']};host={$this->config['db']['host']}";
		$user = $this->config['db']['user'];
		$password = $this->config['db']['password'];
		$db = new PDO($dsn, $user, $password);
		$db->query("SET NAMES utf8");
		return $db;
	}
	
	function select_record_by_auth_token($db, $auth_token)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("SELECT * FROM {$table} WHERE auth_token = ?");
		$st->execute(array($auth_token));
		return $st->fetch();
	}
	function select_record_by_token_credentials($db, $token_credentials)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("SELECT * FROM {$table} WHERE oauth_token = ?");
		$st->execute(array($token_credentials['oauth_token']));
		return $st->fetch();
	}
	function update_record($db, $auth_token, $token_credentials)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("UPDATE {$table} SET auth_token = ? WHERE oauth_token = ?");
		return $st->execute(array($auth_token, $token_credentials['oauth_token']));
	}
	function insert_record($db, $auth_token, $token_credentials)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("INSERT INTO {$table} (auth_token, oauth_token, oauth_token_secret) VALUES (?, ?, ?)");
		return $st->execute(array(
			$auth_token, $token_credentials['oauth_token'], $token_credentials['oauth_token_secret']
		));
	}
	
	function get_auth_token_from_cookie()
	{
		$id = $this->get_identify_number();
		if ($id != null) {
			return $id;
		} else {
			return $this->request['auth_token'];
		}
	}
	function set_auth_token_to_cookie($auth_token)
	{
		$expire = time() + $this->config['auth']['expire'];
		setcookie('auth_token', $auth_token, $expire);
	}
	function generate_auth_token()
	{
		$id = $this->get_identify_number();
		if ($id != null) {
			return $id;
		} else {
			return guid();
		}
	}
	
	function garbage_collect($db)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("DELETE FROM {$table} WHERE modified_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
		return $st->execute();
	}
	
	function get_identify_number()
	{
		if ($_SERVER['HTTP_X_DCMGUID'] != "") {
			//docomo
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			if (preg_match('/docomo\.ne\.jp$/', $host)) {
				return $_SERVER['HTTP_X_DCMGUID'];
			}
			
		} else if ($_SERVER['HTTP_X_UP_SUBNO'] != "") {
			//ezweb
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			if (preg_match('/ezweb\.ne\.jp$/', $host)) {
				return $_SERVER['HTTP_X_UP_SUBNO'];
			}
			
		} else if ($_SERVER['HTTP_X_JPHONE_UID'] != "") {
			//softbank
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			if (preg_match('/jp-[dhtcrknsq]\.ne\.jp/', $host)) {
				return $_SERVER['HTTP_X_JPHONE_UID'];
			}
		}
		return null;
	}
	
	function replace_uri($param)
	{
		//replace uri
		$pattern = '/((?:https?|ftp)(?::\/\/[-_.!~*\'a-zA-Z0-9;\/?:\@&=+\$,%#]+))/i';
		$replace = '<a href="${1}">${1}</a>';
		$param = preg_replace($pattern, $replace, $param);
		//replace screen_name
		$pattern = '/@([0-9A-Z_]+)/i';
		$user_uri = $this->get_uri('user', array('screen_name' => '${1}'));
		$replace = '<a href="' . escape(urldecode($user_uri)) . '">@${1}</a>';
		$param = preg_replace($pattern, $replace, $param);
		//replace hashtag
		$pattern = '/(^|\s)#(\w+)/i';
		$hash_uri = $this->get_uri('search', array('q' => urlencode('#') . '${2}'));
		$replace = '${1}<a href="' . escape(urldecode($hash_uri)) . '">#${2}</a>';
		$param = preg_replace($pattern, $replace, $param);
		return $param;
	}
}
