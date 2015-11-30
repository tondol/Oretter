<?php $this->include_template('header.tpl') ?>

<?php
	$current = $this->get_chain();
	$entries = $this->get('entries');
	$query = $this->get('query');
	$max_id = $this->get('max_id');
	$since_id = $this->get('since_id');
	$post_token = $_SESSION['post_token'];
?>

<h2>タイムライン</h2>

<?php if (is_array($entries) && count($entries) != 0): ?>
	<dl>
		<?php foreach ($entries as $entry): ?>
			<?php $this->set('status', $entry) ?>
			<?php $this->include_template('status.tpl'); ?>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>投稿はありません。</p>
<?php endif; ?>

<h2><a href="#tweet" id="tweet" name="tweet" accesskey="7">[7]検索ワード付きで投稿する</a></h2>
<form action="<?= h($this->get_url('post/tweet')) ?>" method="post">
	<?php
		if ($query != "") {
			$status = ' ' . h($query);
		} else {
			$status = "";
		}
	?>
	<p><textarea name="status"><?= h($status) ?></textarea>
	<br />
	<input type="submit" value="送信" />
	<input type="hidden" name="post_token" value="<?= h($post_token) ?>" /></p>
</form>

<h2><a name="search" id="search">検索ワード</a></h2>
<form action="<?= h($this->get_url()) ?>" method="get">
	<?php
		if ($query != "") {
			$search = h($query);
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
	<li><a href="<?= h($this->get_url(null, $current_params)) ?>" accesskey="0">[0]タイムラインを更新</a></li>
	<?php if ($max_id): ?>
		<li><a href="<?= h($this->get_url(null, $prev_params)) ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($since_id): ?>
		<li><a href="<?= h($this->get_url(null, $next_params)) ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>

<?php $this->include_template('gnavi.tpl') ?>

<hr />

<?php $this->include_template('footer.tpl') ?>
