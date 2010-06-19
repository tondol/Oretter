<?php $this->include_template('header.tpl') ?>

<?php
	$entries = $this->get_assign('entry');
	$current = $this->get_current();
	$query = $this->get_assign('query');
	$post_token = $this->get_assign('post_token');
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
?>

<?php if ($entries instanceof Traversable): ?>
	<dl>
		<?php foreach ($entries as $entry): ?>
			<?php
				preg_match('/^tag:[A-Za-z.]+,[0-9]+:([0-9]+)$/', $entry->id, $matches);
				$id = $matches[1];
				preg_match('/http:\\/\\/twitter.com\\/([0-9A-Za-z_]+)$/', $entry->author->uri, $matches);
				$screen_name = $matches[1];
				$text = $this->replace_uri($entry->title);
				$created_at = strtotime($entry->published);
				$action_params = array(
					'id' => $id,
					'callback' => $callback,
				);
			?>
			<dt>
				<a href="http://twitter.com/<?= escape($screen_name) ?>"><?= escape($screen_name) ?></a>
			</dt>
			<dd>
				<?= $text ?>
			</dd>
			<dd>
				<?= date('m/d H:i', $created_at) ?> -
				<a href="<?= escape($this->get_uri('action', $action_params)) ?>">@action</a>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>つぶやきがありません。</p>
<?php endif; ?>

<h2 id="tweet"><a href="#tweet" accesskey="7">[7]つぶやきを投稿</a></h2>
<form action="<?= escape($this->get_uri('post_tweet')) ?>" method="post">
	<?php
		if ($query != "") {
			$status = ' ' . escape($query);
		} else {
			$status = "";
		}
	?>
	<p><input type="text" name="status" value="<?= escape($status) ?>" />
	<input type="submit" value="送信" />
	<input type="hidden" name="callback" value="<?= escape($callback) ?>" />
	<input type="hidden" name="post_token" value="<?= escape($post_token) ?>" /></p>
</form>

<h2 id="search">検索ワード</h2>
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

<h2 id="bottom">ナビゲーション</h2>
<?php
	$reload_params = array(
		'q' => $query,
	);
	$prev_params = array(
		'q' => $query,
		'p' => $prev,
	);
	$next_params = array(
		'q' => $query,
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
