<?php
require_once("./twitteroauth-master/autoload.php");
require_once("./twitteroauth-master/src/TwitterOAuth.php");
use Abraham\TwitterOAuth\TwitterOAuth;

/*
Twitterにテスト投稿します。
*/

$consumer_key = "";
$consumer_secret = "";
$access_token = "";
$access_secret = "";

//タイムラインの情報をゲット
$Bot = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_secret);
$applying_users = "";

for ($i = 1; $i <= 100; $i++) {
	$statues = $Bot->post("statuses/update", ["status" => "access test1.".rand()]);
	if($statues && $statues->id_str) {
		print_r($statues->id_str);
	}else{
		print_r("SEND ERROR!");
	}
	print_r("\n");
}

?>
