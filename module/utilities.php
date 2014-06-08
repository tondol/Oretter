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
		if (!empty($_SERVER['HTTP_X_DCMGUID'])) {
			//docomo
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			if (preg_match('/docomo\.ne\.jp$/', $host)) {
				return $_SERVER['HTTP_X_DCMGUID'];
			}
			
		} else if (!empty($_SERVER['HTTP_X_UP_SUBNO'])) {
			//ezweb
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			if (preg_match('/ezweb\.ne\.jp$/', $host)) {
				return $_SERVER['HTTP_X_UP_SUBNO'];
			}
			
		} else if (!empty($_SERVER['HTTP_X_JPHONE_UID'])) {
			//softbank
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			if (preg_match('/jp-[dhtcrknsq]\.ne\.jp/', $host)) {
				return $_SERVER['HTTP_X_JPHONE_UID'];
			}
		}
		return null;
	}
	
	function linkify($o)
	{
		$s = $o->text;
		$map = array();
		foreach ($o->entities->urls as $i => $entry) {
			$map[$entry->indices[0]] = [$entry->indices[1], function ($s) use ($entry) {
				return "<a href=\"" . escape($entry->expanded_url) . "\">" . escape($entry->display_url) . "</a>";
			}];
		}
		foreach ($o->entities->user_mentions as $i => $entry) {
			$map[$entry->indices[0]] = [$entry->indices[1], function ($s) use ($entry) {
				return "<a href=\"" . $this->get_uri('user', array('screen_name' => escape($entry->screen_name))) . "\">@" . escape($entry->screen_name) . "</a>";
			}];
		}
		foreach ($o->entities->hashtags as $i => $entry) {
			$map[$entry->indices[0]] = [$entry->indices[1], function ($s) use ($entry) {
				return "<a href=\"" . $this->get_uri('search', array('q' => "#" . escape($entry->text))) . "\">#" . escape($entry->text) . "</a>";
			}];
		}
		if (!empty($o->entities->media)) {
			foreach ($o->entities->media as $i => $entry) {
				$map[$entry->indices[0]] = [$entry->indices[1], function ($s) use ($entry) {
					return "<a href=\"" . escape($entry->expanded_url) . "\">" . escape($entry->display_url) . "</a>";
				}];
			}
		}
		$i = 0; $last = 0;
		$result = '';
		for ($i=0;$i<mb_strlen($s, 'UTF-8');$i++) {
			if (!empty($map[$i])) {
				$index = $map[$i];
				$end = $index[0];
				$f = $index[1];
				if ($i != $last) {
					$result .= escape(mb_substr($s, $last, $i - $last, 'UTF-8'));
				}
				$result .= $f(mb_substr($s, $i, $end - $i, 'UTF-8'));
				$i = $end - 1;
				$last = $end;
			}
		}
		if ($i != $last) {
			$result .= escape(mb_substr($s, $last, $i - $last, 'UTF-8'));
		}
		return $result;
	}
}
