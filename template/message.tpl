<?php $this->include_template('header.tpl') ?>

<?php
	$message = $this->get('message');
	$is_logged_in = !empty($_SESSION['token_credentials']);
	$callback = $_SESSION['callback'];
	$post_token = $_SESSION['post_token'];
?>

<?php if (is_array($message)): ?>
	<p><strong><?= implode('<br />', h($this->get('message'))) ?></strong></p>
<?php else: ?>
	<p><strong><?= h($this->get('message')) ?></strong></p>
<?php endif; ?>

<?php if ($this->get_chain() == "post/tweet"): ?>
	<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]続けて投稿する</a></h2>
	<form action="<?= h($this->get_uri('post/tweet')) ?>" method="post">
		<p><textarea name="status"></textarea>
		<br />
		<input type="submit" value="送信" />
		<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<hr />

<?php if ($is_logged_in): ?>
	<h2>ページナビ</h2>
	<ul>
		<?php if ($callback): ?>
			<li><a href="<?= h($callback) ?>" accesskey="0">[0]元のページに戻る</a></li>
		<?php endif; ?>
		<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
		<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
	</ul>
<?php endif; ?>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
