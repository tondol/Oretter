<?php
	$status = $this->get_assign('status');
	$action_params = array(
		'id' => (string)$status->id,
	);
	$user_params = array(
		'screen_name' => (string)$status->user->screen_name,
	);
	$retweeted_user_params = array(
		'screen_name' => (string)$status->retweeted_status->user->screen_name,
	);
?>
<dt>
	<?php if ($status->retweeted_status): ?>
		<a href="<?= escape($this->get_uri('user', $retweeted_user_params)) ?>">
		<?= escape($status->retweeted_status->user->screen_name) ?>
		</a>
		(RT by <a href="<?= escape($this->get_uri('user', $user_params)) ?>">
		<?= escape($status->user->screen_name) ?>
		</a>)
	<?php else: ?>
		<a href="<?= escape($this->get_uri('user', $user_params)) ?>">
		<?= escape($status->user->screen_name) ?>
		</a>
	<?php endif; ?>
</dt>
<dd>
	<?php if ($status->retweeted_status): ?>
		<?= $this->replace_uri($status->retweeted_status->text) ?>
	<?php else: ?>
		<?= $this->replace_uri($status->text) ?>
	<?php endif; ?>
</dd>
<dd>
	<a href="<?= escape($this->get_uri('action', $action_params)) ?>">
	<?= date('Y-m-d H:i:s', strtotime($status->created_at)) ?>
	</a>
</dd>
