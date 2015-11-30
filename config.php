<?php

define('PHP_DIR', dirname(__FILE__) . '/');
define('SYSTEM_DIR', dirname(__FILE__) . '/');
define('PUBLIC_DIR', SYSTEM_DIR . 'public/');
define('CORE_DIR', PHP_DIR . 'core/');
define('CONTROLLER_DIR', PHP_DIR . 'controller/');
define('TEMPLATE_DIR', PHP_DIR . 'template/');
// define('SPYC_DIR', PHP_DIR . 'spyc/');
define('TWITTER_OAUTH_DIR', PHP_DIR . 'twitteroauth/');

ini_set('display_errors', true);
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('include_path', ini_get('include_path') . ':' . CORE_DIR . ':' . SPYC_DIR . ':' . TWITTER_OAUTH_DIR);
ini_set('date.timezone', "Asia/Tokyo");

// require_once 'Spyc.php';
require_once 'twitteroauth/twitteroauth.php';

$config = array(
	'controller_dir' => CONTROLLER_DIR,
        'template_dir' => TEMPLATE_DIR,
        'public_dir' => PUBLIC_DIR,

        'application_uri' => 'https://oretter.tondol.com/',
        'application_main' => 'top',
        'application_title' => 'Oretter（β）',
        'application_missing' => 'missing',

	'chain' => array(
		'top' => 'トップ',
		'mentions' => 'あなた宛の投稿',
		'search' => '実況ビュー',
		'help' => 'ヘルプ',
		'action' => 'コマンド？',
		'user' => 'ユーザービュー',
		'lists' => 'リストビュー',
		'post/tweet' => '投稿する',
		'post/retweet' => '投稿をRTする',
		'post/destroy' => '投稿を削除する',
		'post/favorite' => 'お気に入り追加',
		'post/unfavorite' => 'お気に入り削除',
		'post/follow' => 'フォローする',
		'post/unfollow' => 'フォローをやめる',
		'info/lists' => '作成したリスト・購読しているリスト',
		'info/lists_ownerships' => '作成したリスト',
		'info/lists_subscriptions' => '購読しているリスト',
		'info/lists_memberships' => '登録されているリスト',
		'info/friendships_friends' => 'フォローしているユーザー',
		'info/friendships_followers' => 'フォローされているユーザー',
		'login' => 'ログイン',
		'logout' => 'ログアウト',
		'missing' => '404 Not Found',
	),

	'twitter' => array(
		'consumer_key' => '4sGVDT9dcp8Miy1BctlrlA',
		'consumer_secret' => 'upu1vxvbr9PudYon5WqyqkQLIqyHJKKsjNAsevz6x0',
	),
	'db' => array(
		'host' => 'mysql',
		'port' => '3306',
		'user' => 'docker',
		'password' => 'jSeqkvS5JUSMH',
		'database' => 'oretter',
	),
	'auth' => array(
		'table' => 'oretter_auth_tokens',
		'expire' => 3600 * 24 * 7,
	),
);
