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
	'application_name' => 'Oretter（β）',
	'application_uri' => 'YOUR_APPLICATION_URI',
	'application_main' => 'top',
	'application_missing' => 'missing',
	//ページIDとページ名称の設定
	'pages' => array(
		'top' => 'トップ',
		'mentions' => 'あなた宛のつぶやき',
		'search' => '実況ビュー',
		'help' => 'ヘルプ',
		'action' => 'コマンド？',
		'post_tweet' => 'つぶやきを投稿',
		'post_retweet' => 'つぶやきをRT',
		'post_favorite' => 'つぶやきをふぁぼる',
		'post_unfavorite' => 'ふぁぼりを取り消す',
		'post_destroy' => 'つぶやきを削除',
		'login' => 'ログイン',
		'logout' => 'ログアウト',
		'auth_get' => '簡易ログイン',
		'auth_set' => '簡易ログインを設定',
		'missing' => '404 Not Found',
	),
	//ユーザー設定
	'twitter' => array(
		'consumer_key' => 'YOUR_CONSUMER_KEY',
		'consumer_secret' => 'YOUR_CONSUMER_SECRET',
	),
	'db' => array(
		'host' => 'YOUR_DB_HOST',
		'user' => 'YOUR_DB_USER',
		'password' => 'YOUR_DB_PASSWORD',
		'dbname' => 'YOUR_DB_DBNAME',
	),
);

?>
