<?php $this->include_template('header.tpl') ?>

<?php
	$statuses = $this->get_assign('status');
	$callback = $this->get_assign('callback');
	$post_token = $this->get_assign('post_token');
	$current = $this->get_current();
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
?>

<?php if ($statuses instanceof Traversable): ?>
	<dl>
		<?php foreach ($statuses as $status): ?>
			<?php
				$action_params = array(
					'id' => (string)$status->id,
					'callback' => $callback,
				);
				$user_params = array(
					'screen_name' => (string)$status->user->screen_name,
				);
			?>
			<dt>
				<a href="<?= escape($this->get_uri('user', $user_params)) ?>">
				<?= escape($status->user->screen_name) ?>
				</a>
			</dt>
			<dd>
				<?= $this->replace_uri($status->text) ?>
			</dd>
			<dd>
				<a href="<?= escape($this->get_uri('action', $action_params)) ?>">
				<?= date('Y-m-d H:i:s', strtotime($status->created_at)) ?>
				</a>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>つぶやきはありません。</p>
<?php endif; ?>

<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]つぶやきを投稿する</a></h2>
<form action="<?= escape($this->get_uri('post_tweet')) ?>" method="post">
	<p><input type="text" name="status" />
	<input type="submit" value="送信" />
	<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<h2><a name="bottom" id="bottom">ナビゲーション</a></h2>
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
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>
<ul>
	<li><a href="<?= escape($this->get_uri('top')) ?>" accesskey="1">[1]トップページ</a></li>
	<li><a href="<?= escape($this->get_uri('mentions')) ?>" accesskey="*">[*]あなた宛のつぶやき</a></li>
	<li><a href="<?= escape($this->get_uri('search')) ?>" accesskey="#">[#]実況ビュー</a></li>
	<li><a href="<?= escape($this->get_uri('auth', array('guid' => 'ON'))) ?>">簡易ログインを設定</a></li>
	<li><a href="<?= escape($this->get_uri('logout')) ?>">ログアウト</a></li>
	<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
</ul>

<?php $this->include_template('footer.tpl') ?>
