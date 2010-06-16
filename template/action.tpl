<?php $this->include_template('header.tpl') ?>

<h1 id="top"><?= $this->get_name() ?></h1>

<?php
	$status = $this->get_assign('status');
	$callback = $this->get_assign('callback');
	$post_token = $this->get_assign('post_token');
?>

<h2 id="reply"><a href="#reply" accesskey="7">[7]つぶやきに返信</a></h2>
<form action="<?= $this->get_uri('post_tweet') ?>" method="post">
	<?php
		$id = escape($status->id);
		$screen_name = escape($status->user->screen_name);
		$reply = '@' . $status->user->screen_name . ' ';
	?>
	<p><input type="text" name="status" value="<?= $reply ?>" />
	<input type="submit" value="返信する" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= $id ?>" />
	<input type="hidden" name="callback" value="<?= $callback ?>" />
	<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
</form>

<?php if ($status->favorited == "true"): ?>
	<h2 id="favorite">ふぁぼりを取り消す</h2>
	<form action="<?= $this->get_uri('post_unfavorite') ?>" method="post">
		<?php
			$id = escape($status->id);
		?>
		<p><input type="submit" value="取り消す" />
		<input type="hidden" name="id" value="<?= $id ?>" />
		<input type="hidden" name="callback" value="<?= $callback ?>" />
		<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
	</form>
<?php else: ?>
	<h2 id="favorite">つぶやきをふぁぼる</h2>
	<form action="<?= $this->get_uri('post_favorite') ?>" method="post">
		<?php
			$id = escape($status->id);
		?>
		<p><input type="submit" value="ふぁぼる" />
		<input type="hidden" name="id" value="<?= $id ?>" />
		<input type="hidden" name="callback" value="<?= $callback ?>" />
		<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
	</form>
<?php endif; ?>

<h2 id="retweet">つぶやきをRT</h2>
<form action="<?= $this->get_uri('post_retweet') ?>" method="post">
	<?php
		$id = escape($status->id);
		$screen_name = escape($status->user->screen_name);
		$text = escape($status->text);
		$retweet = 'RT @' . $status->user->screen_name . ': ' . $text;
	?>
	<p><?= $retweet ?></p>
	<p><input type="submit" value="RTする" />
	<input type="hidden" name="id" value="<?= $id ?>" />
	<input type="hidden" name="callback" value="<?= $callback ?>" />
	<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
</form>

<h2 id="quote">つぶやきをQT</h2>
<form action="<?= $this->get_uri('post_tweet') ?>" method="post">
	<?php
		$id = escape($status->id);
		//protected
		if ($status->user->protected) {
			$screen_name = "***";
		} else {
			$screen_name = escape($status->user->screen_name);
		}
		$text = escape($status->text);
		$quote = ' QT @' . $status->user->screen_name . ': ' . $text;
	?>
	<p><input type="text" name="status" value="<?= $quote ?>" />
	<input type="submit" value="QTする" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= $id ?>" />
	<input type="hidden" name="callback" value="<?= $callback ?>" />
	<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
</form>

<h2>ナビゲーション</h2>
<ul>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>
<ul>
	<li><a href="<?= $callback ?>" accesskey="0">[0]元のページに戻る</a></li>
	<li><a href="<?= $this->get_uri('top') ?>" accesskey="1">[1]トップページに戻る</a></li>
	<li><a href="<?= $this->get_uri('mentions') ?>" accesskey="*">[*]あなた宛のつぶやき</a></li>
	<li><a href="<?= $this->get_uri('search') ?>" accesskey="#">[#]実況ビュー</a></li>
</ul>
<ul>
	<li><a href="<?= $this->get_uri('auth_set') ?>">簡易ログインを設定</a></li>
	<li><a href="<?= $this->get_uri('logout') ?>">ログアウト</a></li>
</ul>

<?php $this->include_template('footer.tpl') ?>
