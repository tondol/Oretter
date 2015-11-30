<?php
	$user = $this->get('user');
	$friendships_params = array(
		'screen_name' => (string)$user->screen_name,
	);
?>
<h2 id="friendships"><?= h($user->screen_name) ?>のフォロー関係</h2>
<dl>
	<dt>フォロー</dt>
	<dd><a href="<?= h($this->get_uri('info/friendships_friends', $friendships_params)) ?>">
		<?= h($user->friends_count) ?>
	</a></dd>
	<dt>フォロワー</dt>
	<dd><a href="<?= h($this->get_uri('info/friendships_followers', $friendships_params)) ?>">
		<?= h($user->followers_count) ?>
	</a></dd>
</dl>
