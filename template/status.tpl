<?php
	$status = $this->get('status');
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
	<?php if ($status->errors): ?>
		閲覧権限がありません	
	<?php elseif ($status->retweeted_status): ?>
		<a href="<?= h($this->get_uri('user', $retweeted_user_params)) ?>"><!--
		--><?= h($status->retweeted_status->user->screen_name) ?><!--
		--></a>
		(RT by <a href="<?= h($this->get_uri('user', $user_params)) ?>"><!--
		--><?= h($status->user->screen_name) ?><!--
		--></a>)
	<?php else: ?>
		<a href="<?= h($this->get_uri('user', $user_params)) ?>"><!--
		--><?= h($status->user->screen_name) ?><!--
		--></a>
	<?php endif; ?>
</dt>
<dd>
	<?php if ($status->errors): ?>
		閲覧権限がありません
	<?php elseif ($status->retweeted_status): ?>
		<?= $this->linkify($status->retweeted_status) ?>
	<?php else: ?>
		<?= $this->linkify($status) ?>
	<?php endif; ?>
</dd>
<dd>
	<a href="<?= h($this->get_uri('action', $action_params)) ?>"><!--
	--><?= date('Y-m-d H:i:s', strtotime($status->created_at)) ?><!--
	--></a>
</dd>
