<?php $this->include_template('header.tpl') ?>

<?php
	$statuses = $this->get_assign('status');
	$target = $this->get_assign('target');
	$user = $this->get_assign('user');
	$callback = $this->get_assign('callback');
	$post_token = $this->get_assign('post_token');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
?>

<dl>
	<dt>名称</dt>
	<dd><?= escape($user->name) ?></dd>
	<dt>場所</dt>
	<dd><?= escape($user->location) ?></dd>
	<?php if ($user->url != ""): ?>
		<dt>ウェブ</dt>
		<dd><a href="<?= escape($user->url) ?>"><?= escape($user->url) ?></a></dd>
	<?php endif; ?>
	<dt>自己紹介</dt>
	<dd><?= escape($user->description) ?></dd>
	<dt>フォローしている</dt>
	<dd><?= escape($user->friends_count) ?></dd>
	<dt>フォローされている</dt>
	<dd><?= escape($user->followers_count) ?></dd>
	<dt>ツイート</dt>
	<dd><?= escape($user->statuses_count) ?></dd>
</dl>

<h2><?= escape($user->screen_name) ?>のつぶやき</h2>

<?php if ($statuses instanceof Traversable): ?>
	<dl>
		<?php foreach ($statuses as $status): ?>
			<?php
				$action_params = array(
					'id' => (string)$status->id,
					'callback' => $callback,
				);
			?>
			<dt>
				<?= escape($user->screen_name) ?>
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

<?php if ($user->id != $_SESSION['token_credentials']['user_id']): ?>
	<?php if ($target->followed_by != "true"): ?>
		<h2 id="follow"><?= escape($user->screen_name) ?>をフォローする</a></h2>
		<form action="<?= escape($this->get_uri('post_follow')) ?>" method="post">
			<p><input type="submit" value="フォローする" />
			<input type="hidden" name="id" value="<?= escape($user->id) ?>" />
			<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
			<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
		</form>
	<?php else: ?>
		<h2 id="follow"><?= escape($user->screen_name) ?>のフォローをやめる</a></h2>
		<form action="<?= escape($this->get_uri('post_unfollow')) ?>" method="post">
			<p><input type="submit" value="フォローをやめる" />
			<input type="hidden" name="id" value="<?= escape($user->id) ?>" />
			<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
			<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
		</form>
	<?php endif; ?>
<?php endif; ?>

<h2><a name="bottom" id="bottom">ナビゲーション</a></h2>
<?php
	$reload_params = array(
		'screen_name' => (string)$user->screen_name,
	);
	$prev_params = array(
		'screen_name' => (string)$user->screen_name,
		'p' => $prev,
	);
	$next_params = array(
		'screen_name' => (string)$user->screen_name,
		'p' => $next,
	);
?>
<ul>
	<li><a href="<?= escape($this->get_uri(null, $reload_params)) ?>" accesskey="0">[0]タイムラインを更新</a></li>
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
	<li><a href="<?= escape($this->get_uri('auth_set', array('guid' => 'ON'))) ?>">簡易ログインを設定</a></li>
	<li><a href="<?= escape($this->get_uri('logout')) ?>">ログアウト</a></li>
	<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
</ul>

<?php $this->include_template('footer.tpl') ?>
