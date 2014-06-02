<?php $this->include_template('header.tpl') ?>

<?php
	$statuses = $this->get_assign('statuses');
	$source = $this->get_assign('source');
	$user = $this->get_assign('user');
	$post_token = $this->get_assign('post_token');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
	$friendships_params = array(
		'screen_name' => (string)$user->screen_name,
	);
	$user_params = array(
		'screen_name' => (string)$user->screen_name,
	);
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
	<dd><a href="<?= escape($this->get_uri('info/friendships_friends', $friendships_params)) ?>">
		<?= escape($user->friends_count) ?>
	</a></dd>
	<dt>フォローされている</dt>
	<dd><a href="<?= escape($this->get_uri('info/friendships_followers', $friendships_params)) ?>">
		<?= escape($user->followers_count) ?>
	</a></dd>
	<dt>ツイート</dt>
	<dd><a href="<?= escape($this->get_uri(null, $user_params)) ?>">
		<?= escape($user->statuses_count) ?>
	</a></dd>
</dl>

<hr />

<h2><?= escape($user->screen_name) ?>のつぶやき</h2>
<?php if (is_array($statuses) && count($statuses) != 0): ?>
	<dl>
		<?php foreach ($statuses as $status): ?>
			<?php $this->set_assign('status', $status) ?>
			<?php $this->include_template('status.tpl'); ?>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>つぶやきはありません。</p>
<?php endif; ?>

<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]<?= escape($user->screen_name) ?>宛につぶやく</a></h2>
<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
	<p><input type="text" name="status" value="@<?= escape($user->screen_name) ?> " />
	<input type="submit" value="送信" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<hr />

<?php $this->include_template('info/lists_navi.tpl'); ?>

<hr />

<?php if ($user->id != $_SESSION['token_credentials']['user_id']): ?>
	<?php if ($source->following != "true"): ?>
		<h2 id="follow"><?= escape($user->screen_name) ?>をフォローする</a></h2>
		<form action="<?= escape($this->get_uri('post/follow')) ?>" method="post">
			<p><input type="submit" value="フォローする" />
			<input type="hidden" name="id" value="<?= escape($user->id) ?>" />
			<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
		</form>
	<?php else: ?>
		<h2 id="follow"><?= escape($user->screen_name) ?>のフォローをやめる</a></h2>
		<form action="<?= escape($this->get_uri('post/unfollow')) ?>" method="post">
			<p><input type="submit" value="フォローをやめる" />
			<input type="hidden" name="id" value="<?= escape($user->id) ?>" />
			<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
		</form>
	<?php endif; ?>
	<hr />
<?php endif; ?>

<h2>ページナビ</h2>
<?php
	$current_params = array(
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
	<li><a href="<?= escape($this->get_uri(null, $current_params)) ?>" accesskey="0">[0]タイムラインを更新</a></li>
	<?php if ($prev): ?>
		<li><a href="<?= escape($this->get_uri(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($next): ?>
		<li><a href="<?= escape($this->get_uri(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
