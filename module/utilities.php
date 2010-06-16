<?php

class Module_utilities extends Module
{
	var $table = 'oretter_auth_tokens';
	var $expire = 604800; //3600*24*7
	
	function initialize_database()
	{
		$dsn = "mysql:dbname={$this->config['db']['dbname']};host={$this->config['db']['host']}";
		$user = $this->config['db']['user'];
		$password = $this->config['db']['password'];
		$db = new PDO($dsn, $user, $password);
		$db->query("SET NAMES utf8");
		return $db;
	}
	
	//load token_credentials by auth_token on database
	function load_token_credentials($db, $auth_token)
	{
		$st = $db->prepare("SELECT * FROM {$this->table} WHERE auth_token = ?");
		$st->execute(array($auth_token));
		return $st->fetch();
	}
	
	//load auth_token by token_credentials on database
	function load_auth_token($db, $token_credentials)
	{
		$st = $db->prepare("SELECT * FROM {$this->table} WHERE oauth_token = ?");
		$st->execute(array($token_credentials['oauth_token']));
		return $st->fetch();
	}
	
	//regenerate auth_token on database
	function regenerate_auth_token($db, $token_credentials)
	{
		$st = $db->prepare("UPDATE {$this->table} SET auth_token = ? WHERE oauth_token = ?");
		$new_token = guid();
		$result = $st->execute(array($new_token, $token_credentials['oauth_token']));
		return $result ? $new_token : false;
	}
	
	//store token_credentials to database
	function store_token_credentials($db, $token_credentials)
	{
		$st = $db->prepare("INSERT INTO {$this->table} (auth_token, oauth_token, oauth_token_secret) VALUES (?, ?, ?)");
		$new_token = guid();
		$result = $st->execute(array(
			$new_token, $token_credentials['oauth_token'], $token_credentials['oauth_token_secret']
		));
		return $result ? $new_token : false;
	}
	
	//store auth_token to cookie
	function store_auth_token($auth_token)
	{
		$expire = time() + $this->expire;
		setcookie('auth_token', $auth_token, $expire);
	}
	
	//delete unused token_credential records
	function garbage_collect($db)
	{
		$st = $db->prepare("DELETE FROM {$this->table} WHERE modified_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
		return $st->execute();
	}
	
	function replace_uri($param)
	{
		$pattern_sub = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/');
        $pattern  = '/((http|https):\/\/[0-9a-z' . $pattern_sub . ']+)/i';
        $replace  = '<a href="\\1">\\1</a>';
        return preg_replace ($pattern, $replace, $param);
	}
}

?>
