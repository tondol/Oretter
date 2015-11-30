<?php $this->include_template('header.tpl') ?>

<?php
	$statuses = $this->get('statuses');
	$source = $this->get('source');
	$user = $this->get('user');
	$prev = $this->get('prev');
	$next = $this->get('next');
	$friendships_params = array(
		'screen_name' => $user->screen_name,
	);
	$user_params = array(
		'screen_name' => $user->screen_name,
	);
	$post_token = $_SESSION['post_token'];
?>

<dl>
	<dt>名称</dt>
	<dd><?= h($user->name) ?></dd>
	<dt>場所</dt>
	<dd><?= h($user->location) ?></dd>
	<?php if ($user->url != ""): ?>
		<dt>ウェブ</dt>
		<dd><a href="<?= h($user->url) ?>"><?= h($user->url) ?></a></dd>
	<?php endif; ?>
	<dt>自己紹介</dt>
	<dd><?= h($user->description) ?></dd>
	<dt>フォローしている</dt>
	<dd><a href="<?= h($this->get_uri('info/friendships_friends', $friendships_params)) ?>">
		<?= h($user->friends_count) ?>
	</a></dd>
	<dt>フォローされている</dt>
	<dd><a href="<?= h($this->get_uri('info/friendships_followers', $friendships_params)) ?>">
		<?= h($user->followers_count) ?>
	</a></dd>
	<dt>ツイート</dt>
	<dd><a href="<?= h($this->get_uri(null, $user_params)) ?>">
		<?= h($user->statuses_count) ?>
	</a></dd>
</dl>

<hr />

<h2><?= h($user->screen_name) ?>の投稿</h2>
<?php if (is_array($statuses) && count($statuses) != 0): ?>
	<dl>
		<?php foreach ($statuses as $status): ?>
			<?php $this->set('status', $status) ?>
			<?php $this->include_template('status.tpl'); ?>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>投稿はありません。</p>
<?php endif; ?>

<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]<?= h($user->screen_name) ?>宛に投稿する</a></h2>
<form action="<?= h($this->get_uri('post/tweet')) ?>" method="post">
	<p><textarea name="status">@<?= h($user->screen_name) ?></textarea>
	<br />
	<input type="submit" value="送信" />
	<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
</form>

<hr />

<?php $this->include_template('info/lists_navi.tpl'); ?>

<hr />

<?php if ($user->id != $_SESSION['token_credentials']['user_id']): ?>
	<?php if ($source->following != "true"): ?>
		<h2 id="follow"><?= h($user->screen_name) ?>をフォローする</a></h2>
		<form action="<?= h($this->get_uri('post/follow')) ?>" method="post">
			<p><input type="submit" value="フォローする" />
			<input type="hidden" name="id" value="<?= h($user->id) ?>" />
			<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
		</form>
	<?php else: ?>
		<h2 id="follow"><?= h($user->screen_name) ?>のフォローをやめる</a></h2>
		<form action="<?= h($this->get_uri('post/unfollow')) ?>" method="post">
			<p><input type="submit" value="フォローをやめる" />
			<input type="hidden" name="id" value="<?= h($user->id) ?>" />
			<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
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
	<li><a href="<?= h($this->get_uri(null, $current_params)) ?>" accesskey="0">[0]タイムラインを更新</a></li>
	<?php if ($prev): ?>
		<li><a href="<?= h($this->get_uri(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($next): ?>
		<li><a href="<?= h($this->get_uri(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
