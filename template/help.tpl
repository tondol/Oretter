<?php $this->include_template('header.tpl') ?>

<?php
	$is_logged_in = $_SESSION['token_credentials'] != "";
?>

<h2>Oretterにログインするには？</h2>
<p>まず、ナビゲーションから「ログイン」を開いてください。</p>
<p>Oretterが連携アプリとしてまだ登録されていない場合：</p>
<p>Twitterのサイトに自動的に移動するので、（Twitterにまだログインしていない場合は）あなたのアカウントでTwitterにログインしてください。<br />
続いて、Oretterがあなたのアカウントにアクセスすることを許可するかどうか聞かれるので、これを許可してください。<br />
すると、Oretterのトップページに自動的に移動します。</p>
<p>Oretterが連携アプリとして既に登録されている場合：</p>
<p>Twitterのサイトに自動的に移動するので、（Twitterにまだログインしていない場合は）あなたのアカウントでTwitterにログインしてください。<br />
すると、Oretterのトップページに自動的に移動します。</p>
<p>これでログイン完了です！</p>

<h2>簡易ログインとは？</h2>
<p>ブラウザを閉じるとOretterからログアウトされてしまう、そんなとき。<br />
Oretterにログインした状態でナビゲーションから「簡易ログインを設定」を開いてみてください。<br />
『簡易ログイン用のトークンを登録しました。』と表示されたら準備完了です。<br />
勝手にログアウトされてしまったときも、「簡易ログイン」を開くことで、Oretterに簡単にログインできるはずです。<br />
ただし、ナビゲーションから「ログアウト」を開いてログアウトしたときは簡易ログインは使えないので注意。</p>

<h2>つぶやきに返信するには？</h2>
<p>つぶやきに返信したいときは、対象のつぶやきの下部にある「つぶやいた日時」のリンクを開いてください。<br />
つぶやきへの返信やお気に入り登録（ふぁぼる）など、つぶやきに対する様々な操作が行えます。</p>

<h2>実況ビューとは？</h2>
<p>あるハッシュタグを追いかけつつ、自分もそのハッシュタグ付きでつぶやける機能です。<br />
Oretterにログインした状態でナビゲーションから「[#]実況ビュー」を開いてみてください。<br />
『検索ワード』の入力欄にハッシュタグを入力して検索ボタンを押すと、そのハッシュタグを含むつぶやきが表示されます。<br />
実況ビューではつぶやきの入力欄に自動でハッシュタグが入力されるため、快適にtsudaることができるでしょう。</p>

<h2>アクセスキーとは？</h2>
<p>ボタンを押すと対応する機能にアクセスできる機能のこと。<br />
例えば『[1]トップページ』というリンクがあったら、「1」のボタンを押してそこに移動することができます。<br />
Oretterの主要な機能にはすべてアクセスキーが設定されているので、うまく活用してみてください。</p>

<h2>誤ってつぶやきを投稿してしまった！</h2>
<p>つぶやきに返信するときと同様に、対象のつぶやきの下部にある「つぶやいた日時」のリンクを開いてください。<br />
それがあなたのつぶやきであれば、「つぶやきを削除する」ボタンが表示されるはずです。</p>

<h2><a name="bottom" id="bottom">ナビゲーション</a></h2>
<?php if ($is_logged_in): ?>
	<ul>
		<li><a href="#top" accesskey="2">[2]ページ先頭に移動</a></li>
		<li><a href="#bottom" accesskey="8">[8]ページ後尾に移動</a></li>
	</ul>
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
		<li><a href="<?= escape($this->get_uri('login')) ?>">ログイン</a></li>
		<li><a href="<?= escape($this->get_uri('auth', array('guid' => 'ON'))) ?>">簡易ログイン</a></li>
		<li><a href="<?= escape($this->get_uri('help')) ?>">ヘルプ</a></li>
	</ul>
<?php endif; ?>

<?php $this->include_template('footer.tpl') ?>
