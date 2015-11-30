<h2><a name="bottom" id="bottom">グローバルナビ</a></h2>
<?php
	$is_logged_in = !empty($_SESSION['token_credentials']);
?>
<?php if ($is_logged_in): ?>
	<ul>
		<li><a href="<?= h($this->get_url('top')) ?>" accesskey="1">[1]トップページ</a></li>
		<li><a href="<?= h($this->get_url('mentions')) ?>" accesskey="*">[*]あなた宛の投稿</a></li>
		<li><a href="<?= h($this->get_url('search')) ?>" accesskey="#">[#]実況ビュー</a></li>
		<li><a href="<?= h($this->get_url('logout')) ?>">ログアウト</a></li>
		<li><a href="<?= h($this->get_url('help')) ?>">ヘルプ</a></li>
	</ul>
<?php else: ?>
	<ul>
		<li><a href="<?= h($this->get_url('top')) ?>" accesskey="1">[1]トップページ</a></li>
		<li><a href="<?= h($this->get_url('login')) ?>" accesskey="*">[*]ログイン</a></li>
		<li><a href="<?= h($this->get_url('help')) ?>">ヘルプ</a></li>
	</ul>
<?php endif; ?>
