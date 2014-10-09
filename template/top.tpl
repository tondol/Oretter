<?php $this->include_template('header.tpl') ?>

<?php
	$statuses = $this->get_assign('statuses');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
	$is_logged_in = array_at($_SESSION, 'token_credentials');
	$post_token = array_at($_SESSION, 'post_token');
?>

<?php if (($rand = mt_rand() % 4) == 0): ?>
	<p><strong>ぼくがかんがえたさいきょうのガラケー向けTwitterクライアント。</strong></p>
<?php elseif ($rand == 1): ?>
	<p><strong>ぼくがかんがえたさいきょうの社畜向けTwitterクライアント。</strong></p>
<?php elseif ($rand == 2): ?>
	<p><strong>事前に簡易ログイン設定をしておけば，中国でもTwitterができる。そう，Oretterならね。</strong></p>
<?php else: ?>
	<p><strong>ソビエトロシアでは、いまどうしてる？があなたをつぶやく！</strong></p>
<?php endif; ?>

<?php if ($is_logged_in): ?>
	<h2>タイムライン</h2>

	<?php if (is_array($statuses) && count($statuses) != 0): ?>
		<dl>
			<?php foreach ($statuses as $status): ?>
				<?php $this->set_assign('status', $status); ?>
				<?php $this->include_template('status.tpl'); ?>
			<?php endforeach; ?>
		</dl>
	<?php elseif (array_at($statuses, 'errors', 0, 'code') == 88): ?>
		<p>Twitter APIの回数制限に到達しました!!</p>
	<?php else: ?>
		<p>つぶやきがありません。</p>
	<?php endif; ?>
	
	<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]つぶやきを投稿する</a></h2>
	<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
		<p><textarea name="status"></textarea>
		<br />
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
