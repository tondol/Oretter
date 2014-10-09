<?php $this->include_template('header.tpl') ?>

<?php
	$lists = $this->get_assign('lists');
	$statuses = $this->get_assign('statuses');
	$id = $this->get_assign('id');
	$screen_name = $this->get_assign('screen_name');
	$current = $this->get_assign('current');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
	$post_token = $_SESSION['post_token'];
?>

<h2>タイムライン</h2>

<?php if (is_array($statuses) && count($statuses) != 0): ?>
	<dl>
		<?php foreach ($statuses as $status): ?>
			<?php
				$this->set_assign('status', $status);
				$this->include_template('status.tpl');
			?>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>投稿がありません。</p>
<?php endif; ?>

<h2><a href="#tweet" name="tweet" id="tweet" accesskey="7">[7]投稿する</a></h2>
<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
	<p><input type="text" name="status" />
	<input type="submit" value="送信" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<hr />

<?php
	$this->set_assign('user', $lists->user);
	$this->include_template('info/lists_navi.tpl');
?>

<hr />

<h2>ページナビ</h2>
<?php
	$current_params = array(
		'id' => $id,
		'screen_name' => $screen_name,
		'p' => $current,
	);
	$prev_params = array(
		'id' => $id,
		'screen_name' => $screen_name,
		'p' => $prev,
	);
	$next_params = array(
		'id' => $id,
		'screen_name' => $screen_name,
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
	<li><a href="#top" accesskey="2">[2]ページ先頭に移動</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
