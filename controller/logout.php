<?php

require_once 'controller_oretter.php';

class Controller_logout extends Controller_oretter
{
	function run()
	{
		session_start();
		$_SESSION = array();
		session_destroy();

		$this->set_auth_token_to_cookie('');

		header('Location: ' . $this->get_uri('top'));
	}
}
