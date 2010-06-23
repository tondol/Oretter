<?php

//ディレクトリの設定
define('SYSTEM_DIR', dirname(__FILE__) . '/');
define('LIB_DIR', SYSTEM_DIR . 'lib/');
define('MODULE_DIR', SYSTEM_DIR . 'module/');
define('TEMPLATE_DIR', SYSTEM_DIR . 'template/');
define('STATIC_DIR', SYSTEM_DIR . 'static/');
//ini_set('display_errors', 1);
ini_set('include_path', ini_get('include_path') . ':' . LIB_DIR);

//フレームワークの設定
//すべてのモジュールから参照できる
$config = array(
	//ディレクトリの設定
	'module_dir' => MODULE_DIR,
	'template_dir' => TEMPLATE_DIR,
	'static_dir' => STATIC_DIR,
	//アプリケーション固有の設定
	'application_name' => 'Oretter.omochi',
	'application_uri' => 'http://omochimetaru.com/oretter/',
	'application_main' => 'top',
	'application_missing' => 'missing',
	
	'autoLoginFlag'=>true,
	'autoCallbackFlag'=>true,
	
	//ページIDとページ名称の設定
	'pages' => array(
		'top' => 'トップ',
		'mentions' => 'あなた宛のつぶやき',
		'search' => '実況ビュー',
		'help' => 'ヘルプ',
		'action' => 'コマンド？',
		'user' => 'ユーザービュー',
		'post_tweet' => 'つぶやきを投稿する',
		'post_retweet' => 'つぶやきをRTする',
		'post_destroy' => 'つぶやきを削除する',
		'post_favorite' => 'ふぁぼる',
		'post_unfavorite' => 'ふぁぼりをやめる',
		'post_follow' => 'フォローする',
		'post_unfollow' => 'フォローをやめる',
		'login' => 'ログイン',
		'logout' => 'ログアウト',
		'auth_get' => '簡易ログイン',
		'auth_set' => '簡易ログインを設定',
		'missing' => '404 Not Found',
	),
	//ユーザー設定
	'twitter' => array(
		'consumer_key' => '********',
		'consumer_secret' => '*******',
	),
	'db' => array(
		'host' => 'mysql68.db.sakura.ne.jp',
		'user' => 'omochimetaru',
		'password' => '********',
		'dbname' => 'omochimetaru',
	),
	'auth' => array(
		'table' => 'oretter_auth_tokens',
		'expire' => 3600 * 24 * 7,
	),
);

?>
