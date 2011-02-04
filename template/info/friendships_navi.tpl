<?php
	$user = $this->get_assign('user');
	$friendships_params = array(
		'screen_name' => (string)$user->screen_name,
	);
?>
<h2 id="friendships"><?= escape($user->screen_name) ?>のフォロー関係</h2>
<dl>
	<dt>フォロー</dt>
	<dd><a href="<?= escape($this->get_uri('info/friendships_friends', $friendships_params)) ?>">
		<?= escape($user->friends_count) ?>
	</a></dd>
	<dt>フォロワー</dt>
	<dd><a href="<?= escape($this->get_uri('info/friendships_followers', $friendships_params)) ?>">
		<?= escape($user->followers_count) ?>
	</a></dd>
</dl>
