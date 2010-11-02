<?php $this->include_template('header.tpl') ?>

<?php
	$user = $this->get_assign('user');
	$friends = $this->get_assign('friends');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
	$callback = $_SESSION['callback'];
?>

<?php if ($friends instanceof Traversable): ?>
	<dl>
		<?php foreach ($friends as $friend): ?>
			<?php
				$friend_params = array(
					'screen_name' => (string)$friend->screen_name,
				);
			?>
			<dt>
				<a href="<?= escape($this->get_uri('user', $friend_params)) ?>"><!--
				--><?= escape($friend->screen_name) ?><!--
				--></a>
			</dt>
			<dd>
				<?= $this->replace_uri($friend->name) ?>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>ユーザーがいません。</p>
<?php endif; ?>

<h2 id="friendships"><?= escape($user->screen_name) ?>のフォロー関係</h2>
<ul>
	<li><a href="<?= escape($this->get_uri('info/friendships_friends')) ?>"><?= escape($user->screen_name) ?>がフォローしているユーザー</a></li>
	<li><a href="<?= escape($this->get_uri('info/friendships_followers')) ?>"><?= escape($user->screen_name) ?>をフォローしているユーザー</a></li>
</ul>

<hr />

<h2>ページナビ</h2>
<?php
	$prev_params = array(
		'cursor' => $prev,
		'screen_name' => $this->request['screen_name'],
	);
	$next_params = array(
		'cursor' => $next,
		'screen_name' => $this->request['screen_name'],
	);
?>
<ul>
	<li><a href="<?= escape($callback) ?>">[0]元のページに戻る</a></li>
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
