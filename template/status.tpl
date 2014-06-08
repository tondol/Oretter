<?php
	$status = $this->get_assign('status');
	$action_params = array(
		'id' => $status->id_str,
	);
	$user_params = array(
		'screen_name' => $status->user->screen_name,
	);
	$retweeted_user_params = array(
		'screen_name' => array_at($status, 'retweeted_status', 'user', 'screen_name'),
	);
?>
<dt>
	<?php if (!empty($status->retweeted_status)): ?>
		<a href="<?= escape($this->get_uri('user', $retweeted_user_params)) ?>"><!--
		--><?= escape($status->retweeted_status->user->screen_name) ?><!--
		--></a>
		(RT by <a href="<?= escape($this->get_uri('user', $user_params)) ?>"><!--
		--><?= escape($status->user->screen_name) ?><!--
		--></a>)
	<?php else: ?>
		<a href="<?= escape($this->get_uri('user', $user_params)) ?>"><!--
		--><?= escape($status->user->screen_name) ?><!--
		--></a>
	<?php endif; ?>
</dt>
<dd>
	<?php if (!empty($status->retweeted_status)): ?>
		<?= $this->linkify($status->retweeted_status) ?>
	<?php else: ?>
		<?= $this->linkify($status) ?>
	<?php endif; ?>
</dd>
<dd>
	<a href="<?= escape($this->get_uri('action', $action_params)) ?>"><!--
	--><?= date('Y-m-d H:i:s', strtotime($status->created_at)) ?><!--
	--></a>
</dd>
