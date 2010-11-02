<?php

require_once 'twitteroauth.php';
require_once dirname(__FILE__) . '/friendships.php';

class Module_info_friendships_followers extends Module_info_friendships
{
	function get_and_parse($connection, $user, $cursor)
	{
		$response = $connection->get(
			'statuses/followers',
			array(
				'cursor' => $cursor,
				'screen_name' => (string)$user->screen_name,
			)
		);
		return @simplexml_load_string($response);
	}
}

?>
