<?php

require_once dirname(__FILE__) . '/utilities.php';

class Module_logout extends Module_utilities
{
	function action()
	{
		session_start();
		$_SESSION = array();
		session_destroy();

		$this->set_auth_token_to_cookie('');

		header('Location: ' . $this->get_uri('top'));
	}
}

?>
