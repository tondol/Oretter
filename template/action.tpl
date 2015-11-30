<?php $this->include_template('header.tpl') ?>

<?php
	$status = $this->get('status');
	$reply = $this->get('reply');
	$user_params = array(
		'screen_name' => $status->user->screen_name,
	);
	$action_params = array(
		'id' => $status->id,
	);
	$post_token = $_SESSION['post_token'];
	$callback = $_SESSION['callback'];
?>

<h2>対象の投稿</h2>

<dl>
	<?php $this->set('status', $status) ?>
	<?php $this->include_template('status.tpl'); ?>
</dl>

<?php if ($reply): ?>
	<h2>返信元の投稿</h2>
	<dl>
		<?php $this->set('status', $reply) ?>
		<?php $this->include_template('status.tpl'); ?>
	</dl>
<?php endif; ?>

<?php if (!empty($status->retweeted_status)): ?>
	<h2>リツイート元の投稿</h2>
	<dl>
		<?php $this->set('status', $status->retweeted_status) ?>
		<?php $this->include_template('status.tpl'); ?>
	</dl>
<?php endif; ?>

<h2><a href="#reply" name="reply" id="reply" accesskey="7">[7]投稿に返信する</a></h2>
<form action="<?= h($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		$reply = '@' . h($status->user->screen_name) . ' ';
	?>
	<p><textarea name="status"><?= $reply ?></textarea>
	<br />
	<input type="submit" value="返信する" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= h($status->id_str) ?>" />
	<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
</form>

<hr />

<?php if ($status->favorited != "true"): ?>
	<h2>お気に入り追加</h2>
	<form action="<?= h($this->get_uri('post/favorite')) ?>" method="post">
		<p><input type="submit" value="追加する" />
		<input type="hidden" name="id" value="<?= h($status->id_str) ?>" />
		<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
	</form>
<?php else: ?>
	<h2>お気に入り削除</h2>
	<form action="<?= h($this->get_uri('post/unfavorite')) ?>" method="post">
		<p><input type="submit" value="削除する" />
		<input type="hidden" name="id" value="<?= h($status->id_str) ?>" />
		<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<h2>投稿を公式RTする</h2>
<form action="<?= h($this->get_uri('post/retweet')) ?>" method="post">
	<p><input type="submit" value="公式RTする" />
	<input type="hidden" name="id" value="<?= h($status->id_str) ?>" />
	<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
</form>

<h2>投稿を非公式RTする</h2>
<form action="<?= h($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		if ($status->user->protected == "true") {
			$quote = ' RT @***: ' . $status->text;
		} else {
			$quote = ' RT @' . h($status->user->screen_name) . ': ' . $status->text;
		}
	?>
	<p><textarea name="status"><?= $quote ?></textarea>
	<br />
	<input type="submit" value="非公式RTする" />
	<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
</form>

<h2>投稿をQTする</h2>
<form action="<?= h($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		if ($status->user->protected == "true") {
			$quote = ' QT @***: ' . $status->text;
		} else {
			$quote = ' QT @' . h($status->user->screen_name) . ': ' . $status->text;
		}
	?>
	<p><textarea name="status"><?= $quote ?></textarea>
	<br />
	<input type="submit" value="QTする" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= h($status->id_str) ?>" />
	<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
</form>

<?php if ($status->user->id == $_SESSION['token_credentials']['user_id']): ?>
	<h2>投稿を削除する</h2>
	<form action="<?= h($this->get_uri('post/destroy')) ?>" method="post">
		<p><input type="submit" value="削除する" />
		<input type="hidden" name="id" value="<?= h($status->id_str) ?>" />
		<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<hr />

<h2>ページナビ</h2>
<ul>
	<li><a href="<?= h($callback) ?>" accesskey="0">[0]元のページに戻る</a></li>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
