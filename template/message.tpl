<?php $this->include_template('header.tpl') ?>

<?php
	$is_logged_in = $_SESSION['token_credentials'] != "";
	$callback = $this->get_assign('callback');
?>

<p><strong><?= $this->get_assign('message') ?></strong></p>

<h2>ナビゲーション</h2>
<?php if ($is_logged_in): ?>
	<ul>
		<?php if ($callback): ?>
			<li><a href="<?= $callback ?>" accesskey="0">[0]元のページに戻る</a></li>
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
<?php else: ?>
	<ul>
		<li><a href="<?= $this->get_uri('top') ?>" accesskey="1">[1]トップページに戻る</a></li>
		<li><a href="<?= $this->get_uri('login') ?>">ログイン</a></li>
		<li><a href="<?= $this->get_uri('auth_get') ?>">簡易ログイン</a></li>
		<li><a href="<?= $this->get_uri('help') ?>">ヘルプ</a></li>
	</ul>
<?php endif; ?>

<?php $this->include_template('footer.tpl') ?>
