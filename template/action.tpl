<?php $this->include_template('header.tpl') ?>

<?php
	$status = $this->get_assign('status');
	$reply = $this->get_assign('reply');
	$callback = $this->get_assign('callback');
	$post_token = $this->get_assign('post_token');
	$user_params = array(
		'screen_name' => (string)$status->user->screen_name,
	);
?>

<dl>
	<dt>
		<a href="<?= escape($this->get_uri('user', $user_params)) ?>">
		<?= escape($status->user->screen_name) ?>
		</a>
	</dt>
	<dd>
		<?= $this->replace_uri($status->text) ?>
	</dd>
	<dd>
		<?= date('Y-m-d H:i:s', strtotime($status->created_at)) ?>
	</dd>
</dl>

<?php if ($reply): ?>
	<?php
		$user_params = array(
			'screen_name' => (string)$reply->user->screen_name,
		);
		$action_params = array(
			'id' => (string)$reply->id,
			'callback' => $callback,
		);
	?>
	<h2>返信元のつぶやき</h2>
	<dl>
		<dt>
			<a href="<?= escape($this->get_uri('user', $user_params)) ?>">
			<?= escape($reply->user->screen_name) ?>
			</a>
		</dt>
		<dd>
			<?= $this->replace_uri($reply->text) ?>
		</dd>
		<dd>
			<a href="<?= escape($this->get_uri('action', $action_params)) ?>">
			<?= date('Y-m-d H:i:s', strtotime($reply->created_at)) ?>
			</a>
		</dd>
	</dl>
<?php endif; ?>

<h2>つぶやきに返信する</h2>
<form action="<?= escape($this->get_uri('post_tweet')) ?>" method="post">
	<?php
		$reply = '@' . escape($status->user->screen_name) . ' ';
	?>
	<p><input type="text" name="status" value="<?= $reply ?>" />
	<input type="submit" value="返信する" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= escape($status->id) ?>" />
	<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<?php if ($status->favorited != "true"): ?>
	<h2>つぶやきをふぁぼる</h2>
	<form action="<?= escape($this->get_uri('post_favorite')) ?>" method="post">
		<p><input type="submit" value="ふぁぼる" />
		<input type="hidden" name="id" value="<?= escape($status->id) ?>" />
		<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php else: ?>
	<h2>ふぁぼりを取り消す</h2>
	<form action="<?= escape($this->get_uri('post_unfavorite')) ?>" method="post">
		<p><input type="submit" value="取り消す" />
		<input type="hidden" name="id" value="<?= escape($status->id) ?>" />
		<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<h2>つぶやきをRTする</h2>
<form action="<?= escape($this->get_uri('post_retweet')) ?>" method="post">
	<p><input type="submit" value="RTする" />
	<input type="hidden" name="id" value="<?= escape($status->id) ?>" />
	<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<h2>つぶやきをQTする</h2>
<form action="<?= escape($this->get_uri('post_tweet')) ?>" method="post">
	<?php
		if ($status->user->protected == "true") {
			$quote = ' QT @***: ' . $status->text;
		} else {
			$quote = ' QT @' . escape($status->user->screen_name) . ': ' . $status->text;
		}
	?>
	<p><input type="text" name="status" value="<?= $quote ?>" />
	<input type="submit" value="QTする" />
	<input type="hidden" name="in_reply_to_status_id" value="<?= escape($status->id) ?>" />
	<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<?php if ($status->user->id == $_SESSION['token_credentials']['user_id']): ?>
	<h2>つぶやきを削除する</h2>
	<form action="<?= escape($this->get_uri('post_destroy')) ?>" method="post">
		<p><input type="submit" value="削除する" />
		<input type="hidden" name="id" value="<?= escape($status->id) ?>" />
		<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
		<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
	</form>
<?php endif; ?>

<h2><a name="bottom" id="bottom">ナビゲーション</a></h2>
<ul>
	<li><a href="<?= escape($callback) ?>" accesskey="0">[0]元のページに戻る</a></li>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>
<ul>
	<li><a href="<?= escape($this->get_uri('top')) ?>" accesskey="1">[1]トップページ</a></li>
	<li><a href="<?= escape($this->get_uri('mentions')) ?>" accesskey="*">[*]あなた宛のつぶやき</a></li>
	<li><a href="<?= escape($this->get_uri('search')) ?>" accesskey="#">[#]実況ビュー</a></li>
	<li><a href="<?= escape($this->get_uri('auth_set', array('guid' => 'ON'))) ?>">簡易ログインを設定</a></li>
	<li><a href="<?= escape($this->get_uri('logout')) ?>">ログアウト</a></li>
	<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
</ul>

<?php $this->include_template('footer.tpl') ?>
