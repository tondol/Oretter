<?php
	$user = $this->get_assign('user');
	$lists_params = array(
		'screen_name' => (string)$user->screen_name,
	);
?>
<h2 id="list"><?= escape($user->screen_name) ?>のリスト</h2>
<ul>
	<li><a href="<?= escape($this->get_uri('info/lists', $lists_params)) ?>">
		作成したリスト
	</a></li>
	<li><a href="<?= escape($this->get_uri('info/lists_subscriptions', $lists_params)) ?>">
		フォローしているリスト
	</a></li>
	<li><a href="<?= escape($this->get_uri('info/lists_memberships', $lists_params)) ?>">
		フォローされているリスト
	</a></li>
</ul>
