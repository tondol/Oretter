<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/lists.php';

class Module_info_lists_subscriptions extends Module_info_lists
{
	function get_and_parse($connection, $user, $cursor)
	{
		$response = $connection->get(
			(string)$user->screen_name . '/lists/subscriptions',
			array('cursor' => $cursor)
		);
		return @simplexml_load_string($response);
	}
}

?>
