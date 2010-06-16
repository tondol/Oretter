<?php $this->include_template('header.tpl') ?>

<?php
	session_start();
	$is_logged_in = $_SESSION['token_credentials'] != "";
?>

<h2>Oretterにログインするには？</h2>
<p>まず、ナビゲーションから「ログイン」を開く。</p>
<p>Oretterが連携アプリとしてまだ登録されていない場合：<br />
Twitterのサイトに自動的に移動する。<br />
（Twitterにまだログインしていない場合）あなたのアカウントでTwitterにログインする。<br />
Oretterがあなたのアカウントにアクセスすることを許可する。<br />
Oretterのトップページに自動的に移動する。</p>
<p>Oretterが連携アプリとして既に登録されている場合：<br />
Twitterのサイトに自動的に移動する。<br />
（Twitterにまだログインしていない場合）あなたのアカウントでTwitterにログインする。<br />
Oretterのトップページに自動的に移動する。</p>
<p>これでログイン完了！</p>

<h2>簡易ログインとは？</h2>
<p>ブラウザを閉じるとOretterからログアウトされてしまう、そんなとき。<br />
Oretterにログインした状態でナビゲーションから「簡易ログインを設定」を開いてみよう。<br />
『簡易ログイン用のトークンを登録しました。』と表示されたら準備完了。<br />
勝手にログアウトされてしまったときも、「簡易ログイン」を開くことで、Oretterに簡単にログインできる。<br />
ナビゲーションから「ログアウト」を開いてログアウトしたときは簡易ログインは使えないので注意。</p>

<h2>つぶやきに返信するには？</h2>
<p>つぶやきに返信したいときは、つぶやき下部の「@action」というリンクを開く。<br />
つぶやきへの返信やお気に入り登録（ふぁぼる）など、つぶやきに対する様々な操作ができる。</p>

<h2>実況ビューとは？</h2>
<p>あるハッシュタグを追いかけつつ、自分もそのハッシュタグ付きでつぶやける機能。<br />
Oretterにログインした状態でナビゲーションから「[#]実況ビュー」を開いてみよう。<br />
『検索ワード』の入力欄にハッシュタグを入力して検索ボタンを押すと、そのハッシュタグを含むつぶやきが表示される。<br />
『[7]つぶやきを投稿』の入力欄には自動でハッシュタグが入力されるため、快適にtsudaることができる。</p>

<h2>アクセスキーとは？</h2>
<p>ボタンを押すと対応する機能にアクセスできる機能のこと。<br />
『[1]トップページ』←「1」のボタンを押して移動できる</a><br />
『[2]ページ先頭に移動』←「2」のボタンを押して移動できる</a><br />
『[8]ページ後尾に移動』←「8」のボタンを押して移動できる</a><br />
Oretterの主要な機能にはすべてアクセスキーが設定されているので活用してね。</p>

<h2 id="bottom">ナビゲーション</h2>
<?php if ($is_logged_in): ?>
	<ul>
		<li><a href="#top" accesskey="2">[2]ページ先頭に移動</a></li>
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
		<li><a href="<?= $this->get_uri('top') ?>" accesskey="1">[1]トップページ</a></li>
		<li><a href="<?= $this->get_uri('login') ?>">ログイン</a></li>
		<li><a href="<?= $this->get_uri('auth_get') ?>">簡易ログイン</a></li>
		<li><a href="<?= $this->get_uri('help') ?>">ヘルプ</a></li>
	</ul>
<?php endif; ?>

<?php $this->include_template('footer.tpl') ?>
