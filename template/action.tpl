<?php $this->include_template('header.tpl') ?>

<?php
	$status = $this->get_assign('status');
	$reply = $this->get_assign('reply');
	$post_token = $this->get_assign('post_token');
	$callback = $_SESSION['callback'];
	$user_params = array(
		'screen_name' => (string)$status->user->screen_name,
	);
	$action_params = array(
		'id' => (string)$status->id,
	);
?>

<h2>対象のつぶやき</h2

<dl>
	<?php $this->set_assign('status', $status) ?>
	<?php $this->include_template('status.tpl'); ?>
</dl>

<?php if ($reply): ?>
	<h2>返信元のつぶやき</h2>
	<dl>
		<?php $this->set_assign('status', $reply) ?>
		<?php $this->include_template('status.tpl'); ?>
	</dl>
<?php endif; ?>

<?php if ($status->retweeted_status): ?>
	<h2>リツイート元のつぶやき</h2>
	<dl>
		<?php $this->set_assign('status', $status->retweeted_status) ?>
		<?php $this->include_template('status.tpl'); ?>
	</dl>
<?php endif; ?>

<h2><a href="#reply" name="reply" id="reply" accesskey="7">[7]つぶやきに返信する</a></h2>
<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		$reply = '@' . escape($status->user->screen_name) . ' ';
	?>
	<p><input type="text" name="status" value="<?= $reply ?>" />
	<input type="submit" value="返信する" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= escape($status->id_str) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<hr />

<?php if ($status->favorited != "true"): ?>
	<h2>つぶやきをふぁぼる</h2>
	<form action="<?= escape($this->get_uri('post/favorite')) ?>" method="post">
		<p><input type="submit" value="ふぁぼる" />
		<input type="hidden" name="id" value="<?= escape($status->id_str) ?>" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php else: ?>
	<h2>ふぁぼりを取り消す</h2>
	<form action="<?= escape($this->get_uri('post/unfavorite')) ?>" method="post">
		<p><input type="submit" value="取り消す" />
		<input type="hidden" name="id" value="<?= escape($status->id_str) ?>" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<h2>つぶやきを公式RTする</h2>
<form action="<?= escape($this->get_uri('post/retweet')) ?>" method="post">
	<p><input type="submit" value="公式RTする" />
	<input type="hidden" name="id" value="<?= escape($status->id_str) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<h2>つぶやきを非公式RTする</h2>
<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		if ($status->user->protected == "true") {
			$quote = ' RT @***: ' . $status->text;
		} else {
			$quote = ' RT @' . escape($status->user->screen_name) . ': ' . $status->text;
		}
	?>
	<p><input type="text" name="status" value="<?= $quote ?>" />
	<input type="submit" value="非公式RTする" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<h2>つぶやきをQTする</h2>
<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		if ($status->user->protected == "true") {
			$quote = ' QT @***: ' . $status->text;
		} else {
			$quote = ' QT @' . escape($status->user->screen_name) . ': ' . $status->text;
		}
	?>
	<p><input type="text" name="status" value="<?= $quote ?>" />
	<input type="submit" value="QTする" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= escape($status->id_str) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<?php if ($status->user->id == $_SESSION['token_credentials']['user_id']): ?>
	<h2>つぶやきを削除する</h2>
	<form action="<?= escape($this->get_uri('post/destroy')) ?>" method="post">
		<p><input type="submit" value="削除する" />
		<input type="hidden" name="id" value="<?= escape($status->id_str) ?>" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<hr />

<h2>ページナビ</h2>
<ul>
	<li><a href="<?= escape($callback) ?>" accesskey="0">[0]元のページに戻る</a></li>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
