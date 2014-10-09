<?php $this->include_template('header.tpl') ?>

<?php
	$current = $this->get_current();
	$entries = $this->get_assign('entries');
	$query = $this->get_assign('query');
	$max_id = $this->get_assign('max_id');
	$since_id = $this->get_assign('since_id');
	$post_token = $_SESSION['post_token'];
?>

<h2>タイムライン</h2>

<?php if (is_array($entries) && count($entries) != 0): ?>
	<dl>
		<?php foreach ($entries as $entry): ?>
			<?php $this->set_assign('status', $entry) ?>
			<?php $this->include_template('status.tpl'); ?>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>つぶやきはありません。</p>
<?php endif; ?>

<h2><a href="#tweet" id="tweet" name="tweet" accesskey="7">[7]検索ワード付きでつぶやく</a></h2>
<form action="<?= escape($this->get_uri('post/tweet')) ?>" method="post">
	<?php
		if ($query != "") {
			$status = ' ' . escape($query);
		} else {
			$status = "";
		}
	?>
	<p><textarea name="status"><?= escape($status) ?></textarea>
	<br />
	<input type="submit" value="送信" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<h2><a name="search" id="search">検索ワード</a></h2>
<form action="<?= escape($this->get_uri()) ?>" method="post">
	<?php
		if ($query != "") {
			$search = escape($query);
		} else {
			$search = "#";
		}
	?>
	<p><input type="text" name="q" value="<?= $search ?>" />
	<input type="submit" value="検索" /></p>
</form>

<hr />

<h2>ページナビ</h2>
<?php
	$current_params = array(
		'q' => $query,
	);
	$prev_params = array(
		'q' => $query,
		'since_id' => $max_id,
	);
	$next_params = array(
		'q' => $query,
		'max_id' => $since_id,
	);
?>
<ul>
	<li><a href="<?= escape($this->get_uri(null, $current_params)) ?>" accesskey="0">[0]タイムラインを更新</a></li>
	<?php if ($max_id): ?>
		<li><a href="<?= escape($this->get_uri(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($since_id): ?>
		<li><a href="<?= escape($this->get_uri(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
