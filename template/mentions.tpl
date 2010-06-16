<?php $this->include_template('header.tpl') ?>

<?php
	$statuses = $this->get_assign('status');
	$callback = $this->get_assign('callback');
	$post_token = $this->get_assign('post_token');
	$current = $this->get_current();
	$prev = $this->get_assign('prev');
	$next = $this->get_assign('next');
?>

<?php if ($statuses instanceof Traversable): ?>
	<dl>
		<?php foreach ($statuses as $status): ?>
			<?php
				$id = escape($status->id);
				$screen_name = escape($status->user->screen_name);
				//エスケープするとむしろ不具合
				$text = nl2br($this->replace_uri($status->text));
				$created_at = strtotime($status->created_at);
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
	<p>つぶやきはありません。</p>
<?php endif; ?>

<h2 id="tweet"><a href="#tweet" accesskey="7">[7]つぶやきを投稿</a></h2>
<form action="<?= $this->get_uri('post_tweet') ?>" method="post">
	<p><input type="text" name="status" />
	<input type="submit" value="送信" />
	<input type="hidden" name="callback" value="<?= $callback ?>" />
	<input type="hidden" name="post_token" value="<?= $post_token ?>" /></p>
</form>

<h2 id="bottom">ナビゲーション</h2>
<?php
	$prev_query = http_build_query(array(
		'page' => $current,
		'p' => $prev,
	), '', '&amp;');
	$next_query = http_build_query(array(
		'page' => $current,
		'p' => $next,
	), '', '&amp;');
?>
<ul>
	<li><a href="<?= $this->get_uri() ?>" accesskey="0">[0]タイムラインを更新</a></li>
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
	<li><a href="<?= $this->get_uri('top') ?>" accesskey="1">[1]トップページに戻る</a></li>
	<li><a href="<?= $this->get_uri('mentions') ?>" accesskey="*">[*]あなた宛のつぶやき</a></li>
	<li><a href="<?= $this->get_uri('search') ?>" accesskey="#">[#]実況ビュー</a></li>
	<li><a href="<?= $this->get_uri('auth_set') ?>">簡易ログインを設定</a></li>
	<li><a href="<?= $this->get_uri('logout') ?>">ログアウト</a></li>
	<li><a href="<?= $this->get_uri('help') ?>">ヘルプ</a></li>
</ul>

<?php $this->include_template('footer.tpl') ?>
