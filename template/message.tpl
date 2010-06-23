<?php
	if($this->config["autoCallbackFlag"]){
		$append="?cbMsg=".urlencode($this->get_assign('message'));
		$callback=$this->get_assign('callback');
		if(strpos($callback,"?")!==false){
			$callback=str_replace("?",$append."&",$callback);
		}else
		{
			$callback.=$append;
		}
		header('Location: ' . $callback);
		exit(1);
	}
?>

<?php $this->include_template('header.tpl') ?>

<?php
	$is_logged_in = $_SESSION['token_credentials'] != "";
	$callback = $this->get_assign('callback');
?>

<p><strong><?= escape($this->get_assign('message')) ?></strong></p>

<h2><a name="bottom" id="bottom">ナビゲーション</a></h2>
<?php if ($is_logged_in): ?>
	<ul>
		<?php if ($callback): ?>
			<li><a href="<?= escape($callback) ?>" accesskey="0">[0]元のページに戻る</a></li>
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
<?php else: ?>
	<ul>
		<li><a href="<?= escape($this->get_uri('top')) ?>" accesskey="1">[1]トップページ</a></li>
		<li><a href="<?= escape($this->get_uri('login')) ?>" accesskey="*">[*]ログイン</a></li>
		<li><a href="<?= escape($this->get_uri('auth_get', array('guid' => 'ON'))) ?>" accesskey="#">[#]簡易ログイン</a></li>
		<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
	</ul>
<?php endif; ?>

<?php $this->include_template('footer.tpl') ?>
