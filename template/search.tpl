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
				//正規表現でstatus_idを取り出す
				preg_match('/^tag:[A-Za-z.]+,[0-9]+:([0-9]+)$/', $entry->id, $matches);
				$id = escape($matches[1]);
				//正規表現でscreen_nameを取り出す
				preg_match('/http:\\/\\/twitter.com\\/([0-9A-Za-z_]+)$/', $entry->author->uri, $matches);
				$screen_name = escape($matches[1]);
				//エスケープするとむしろ不具合
				$text = nl2br($this->replace_uri($entry->title));
				$created_at = strtotime($entry->published);
				$params = http_build_query(array(
					'page' => 'action',
					'id' => $id,
					'callback' => $callback,
				));
				$action = $this->get_uri('top') . '?' . $params;
			?>
			<dt>
				<a href="http://twitter.com/<?= $screen_name ?>"><?= $screen_name ?></a>
			</dt>
			<dd>
				<?= $text ?><br />
				<?= date('m/d H:i', $created_at) ?> -
				<a href="<?= $action ?>">@action</a>
			</dd>
		<?php endforeach; ?>
	</dl>
<?php else: ?>
	<p>つぶやきがありません。</p>
<?php endif; ?>

<h2 id="tweet"><a href="#tweet" accesskey="7">[7]つぶやきを投稿</a></h2>
<form action="<?= $this->get_uri('post_tweet') ?>" method="post">
	<?php
		$status = $query != "" ? ' ' . $query : '';
	?>
	<p><input type="text" name="status" value="<?= $status ?>" />
	<input type="submit" value="送信" />
	<input type="hidden" name="callback" value="<?= $callback ?>" />
	<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
</form>

<h2 id="search">検索ワード</h2>
<form action="<?= $this->get_uri('top') ?>" method="get">
	<?php
		$search = $query != "" ? $query : '#';
	?>
	<p><input type="text" name="q" value="<?= $search ?>" />
	<input type="hidden" name="page" value="<?= $current ?>" />
	<input type="submit" value="検索" /></p>
</form>

<h2>ナビゲーション</h2>
<?php
	$reload_query = http_build_query(array(
		'page' => $current,
		'q' => $query,
	), '', '&amp;');
	$prev_query = http_build_query(array(
		'page' => $current,
		'q' => $query,
		'p' => $prev,
	), '', '&amp;');
	$next_query = http_build_query(array(
		'page' => $current,
		'q' => $query,
		'p' => $next,
	), '', '&amp;');
?>
<ul>
	<li><a href="<?= $this->get_uri('top') ?>?<?= $reload_query ?>" accesskey="0">[0]タイムラインを更新</a></li>
	<?php if ($prev): ?>
		<li><a href="<?= $this->get_uri('top') ?>?<?= $prev_query ?>" accesskey="4">[4]前を見る</a></li>
	<?php endif; ?>
	<?php if ($next): ?>
		<li><a href="<?= $this->get_uri('top') ?>?<?= $next_query ?>" accesskey="6">[6]次を見る</a></li>
	<?php endif; ?>
	<li><a href="#top" accesskey="2">[2]ページ先頭に戻る</a></li>
	<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
</ul>
<ul>
	<li><a href="<?= $this->get_uri('top') ?>" accesskey="1">[1]トップページ</a></li>
	<li><a href="<?= $this->get_uri('mentions') ?>" accesskey="*">[*]あなた宛のつぶやき</a></li>
	<li><a href="<?= $this->get_uri('search') ?>" accesskey="#">[#]実況ビュー</a></li>
	<li><a href="<?= $this->get_uri('auth_set') ?>">簡易ログインを設定</a></li>
	<li><a href="<?= $this->get_uri('logout') ?>">ログアウト</a></li>
	<li><a href="<?= $this->get_uri('help') ?>">ヘルプ</a></li>
</ul>

<?php $this->include_template('footer.tpl') ?>
