<h2><a name="bottom" id="bottom">グローバルナビ</a></h2>
<?php
	$token_credentials = $_SESSION['token_credentials'];
	$is_logged_in = $token_credentials != "";
?>
<?php if ($is_logged_in): ?>
	<ul>
		<li><a href="<?= escape($this->get_uri('top')) ?>" accesskey="1">[1]トップページ</a></li>
		<li><a href="<?= escape($this->get_uri('mentions')) ?>" accesskey="*">[*]あなた宛のつぶやき</a></li>
		<li><a href="<?= escape($this->get_uri('search')) ?>" accesskey="#">[#]実況ビュー</a></li>
		<li><a href="<?= escape($this->get_uri('auth', array('guid' => 'ON'))) ?>">簡易ログインを設定</a></li>
		<li><a href="<?= escape($this->get_uri('logout')) ?>">ログアウト</a></li>
		<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
	</ul>
<?php else: ?>
	<ul>
		<li><a href="<?= escape($this->get_uri('top')) ?>" accesskey="1">[1]トップページ</a></li>
		<li><a href="<?= escape($this->get_uri('login')) ?>" accesskey="*">[*]ログイン</a></li>
		<li><a href="<?= escape($this->get_uri('auth', array('guid' => 'ON'))) ?>" accesskey="#">[#]簡易ログイン</a></li>
		<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
	</ul>
<?php endif; ?>
