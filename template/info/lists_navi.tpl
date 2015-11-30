<?php
	$user = $this->get('user');
	$lists_params = array(
		'screen_name' => (string)$user->screen_name,
	);
?>
<h2 id="list"><?= h($user->screen_name) ?>のリスト</h2>
<ul>
	<li><a href="<?= h($this->get_url('info/lists', $lists_params)) ?>">
		作成したリスト・購読しているリスト
	</a></li>
	<li><a href="<?= h($this->get_url('info/lists_ownerships', $lists_params)) ?>">
		作成したリスト
	</a></li>
	<li><a href="<?= h($this->get_url('info/lists_subscriptions', $lists_params)) ?>">
		購読しているリスト
	</a></li>
	<li><a href="<?= h($this->get_url('info/lists_memberships', $lists_params)) ?>">
		登録されているリスト
	</a></li>
</ul>
