<?php $this->include_template('header.tpl') ?>

<?php
	$is_logged_in = $_SESSION['token_credentials'] != "";
	$statuses = $this->get_assign('statuses');
	$post_token = $this->get_assign('post_token');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
?>

<?php if (mt_rand() % 2 == 0): ?>
	<p><strong>ぼくがかんがえたさいきょうのガラケー向けTwitterクライアント。</strong></p>
<?php else: ?>
	<p><strong>ソビエトロシアでは、いまどうしてる？があなたをつぶやく！</strong></p>
<?php endif; ?>

<?php if ($is_logged_in): ?>
	<?php if ($statuses instanceof Traversable): ?>
		<dl>
			<?php foreach ($statuses as $status): ?>
				<?php $this->set_assign('status', $status); ?>
				<?php $this->include_template('status.tpl'); ?>
			<?php endforeach; ?>
		</dl>
	<?php else: ?>
		<p>つぶやきがありません。</p>
	<?php endif; ?>
	<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]つぶやきを投稿する</a></h2>
	<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
		<p><input type="text" name="status" />
		<input type="submit" value="送信" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<hr />

<?php if ($is_logged_in): ?>
	<h2>ページナビ</h2>
	<?php
		$prev_params = array(
			'p' => $prev,
		);
		$next_params = array(
			'p' => $next,
		);
	?>
	<ul>
		<li><a href="<?= escape($this->get_uri()) ?>" accesskey="0">[0]タイムラインを更新</a></li>
		<?php if ($prev): ?>
			<li><a href="<?= escape($this->get_uri(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
		<?php endif; ?>
		<?php if ($next): ?>
			<li><a href="<?= escape($this->get_uri(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
		<?php endif; ?>
		<li><a href="#top" accesskey="2">[2]ページ先頭に移動</a></li>
		<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
	</ul>
<?php endif; ?>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
