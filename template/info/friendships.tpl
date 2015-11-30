<?php $this->include_template('header.tpl') ?>

<?php
	$user = $this->get('user');
	$friends = $this->get('friends');
	$prev = $this->get('prev');
	$next = $this->get('next');
	$callback = $_SESSION['callback'];
?>

<h2>ユーザー一覧</h2>

<?php if (is_array($friends) && count($friends) != 0): ?>
	<dl>
		<?php foreach ($friends as $friend): ?>
			<?php
				$friend_params = array(
					'screen_name' => (string)$friend->screen_name,
				);
			?>
			<dt>
				<a href="<?= h($this->get_uri('user', $friend_params)) ?>"><!--
				--><?= h($friend->screen_name) ?><!--
				--></a>
			</dt>
			<dd>
				<?= h($friend->name) ?>
			</dd>
			<dd>
				<strong><?= $friend->following == "true" ? "フォロー中" : "未フォロー" ?></strong>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>ユーザーがいません。</p>
<?php endif; ?>

<hr />

<?php $this->include_template('info/friendships_navi.tpl'); ?>

<hr />

<h2>ページナビ</h2>
<?php
	$prev_params = array(
		'cursor' => $prev,
		'screen_name' => $this->get['screen_name'],
	);
	$next_params = array(
		'cursor' => $next,
		'screen_name' => $this->get['screen_name'],
	);
?>
<ul>
	<li><a href="<?= h($callback) ?>">[0]元のページに戻る</a></li>
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
