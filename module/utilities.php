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
	
	//load record by auth_token
	function load_record_by_auth_token($db, $auth_token)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("SELECT * FROM {$table} WHERE auth_token = ?");
		$st->execute(array($auth_token));
		return $st->fetch();
	}
	
	//load record by token_credentials
	function load_record_by_token_credentials($db, $token_credentials)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("SELECT * FROM {$table} WHERE oauth_token = ?");
		$st->execute(array($token_credentials['oauth_token']));
		return $st->fetch();
	}
	
	//regenerate record
	function update_record($db, $auth_token, $token_credentials)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("UPDATE {$table} SET auth_token = ? WHERE oauth_token = ?");
		return $st->execute(array($auth_token, $token_credentials['oauth_token']));
	}
	
	//store record
	function store_record($db, $auth_token, $token_credentials)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("INSERT INTO {$table} (auth_token, oauth_token, oauth_token_secret) VALUES (?, ?, ?)");
		return $st->execute(array(
			$auth_token, $token_credentials['oauth_token'], $token_credentials['oauth_token_secret']
		));
	}
	
	//load auth_token
	function load_auth_token()
	{
		$id = $this->get_identify_number();
		if ($id != null) {
			return $id;
		} else {
			return $this->request['auth_token'];
		}
	}
	
	//generate auth_token
	function generate_auth_token()
	{
		$id = $this->get_identify_number();
		if ($id != null) {
			return $id;
		} else {
			return guid();
		}
	}
	
	//store auth_token cookie
	function store_auth_token($auth_token)
	{
		$expire = time() + $this->config['auth']['expire'];
		setcookie('auth_token', $auth_token, $expire);
	}
	
	//delete unused token_credential records
	function garbage_collect($db)
	{
		$table = $this->config['auth']['table'];
		$st = $db->prepare("DELETE FROM {$table} WHERE modified_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
		return $st->execute();
	}
	
	//get identify number of mobile phone
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
	
	//replace uri text with anchor tag
	function replace_uri($param)
	{
		$pattern_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/');
        $pattern  = '/((http|https):\/\/[0-9a-z' . $pattern_sub . ']+)/i';
        $replace  = '<a href="\\1">\\1</a>';
        return preg_replace ($pattern, $replace, $param);
	}
}

?>
