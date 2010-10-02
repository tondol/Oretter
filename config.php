<?php

//ディレクトリの設定
//基本的に弄る必要はないはず
define('SYSTEM_DIR', dirname(__FILE__) . '/');
define('LIB_DIR', SYSTEM_DIR . 'lib/');
define('MODULE_DIR', SYSTEM_DIR . 'module/');
define('TEMPLATE_DIR', SYSTEM_DIR . 'template/');
define('STATIC_DIR', SYSTEM_DIR . 'static/');
//ini_set('display_errors', true);
ini_set('include_path', ini_get('include_path') . ':' . LIB_DIR);
date_default_timezone_set('Asia/Tokyo');

//フレームワークの設定
//すべてのモジュールから参照できる
$config = array(
	//ディレクトリの設定
	'module_dir' => MODULE_DIR,
	'template_dir' => TEMPLATE_DIR,
	'static_dir' => STATIC_DIR,
	//アプリケーション固有の設定
	'application_name' => 'Oretter（β）',
	'application_uri' => 'http://digilog.usamimi.info/oretter/',
	'application_main' => 'top',
	'application_missing' => 'missing',
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
		'auth' => '簡易ログイン',
		'missing' => '404 Not Found',
	),
	//ユーザー設定
	'twitter' => array(
		'consumer_key' => 'YOUR_CONSUMER_KEY',
		'consumer_secret' => 'YOUR_CONSUMER_SECRET',
	),
	'db' => array(
		'host' => 'DB_HOST',
		'user' => 'DB_USER',
		'password' => 'DB_PASSWORD',
		'dbname' => 'DB_NAME',
	),
	'auth' => array(
		'table' => 'oretter_auth_tokens',
		'expire' => 3600 * 24 * 7,
	),
);

?>
