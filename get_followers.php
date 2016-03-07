<?php
require_once("./twitteroauth-master/autoload.php");
require_once("./twitteroauth-master/src/TwitterOAuth.php");
use Abraham\TwitterOAuth\TwitterOAuth;

/*
対象のアカウントのフォローユーザをまとめて取得します。
API制限上、一度に取得できる量は200件*15回=3000件のようです。(chartには30回とありましたが何か問題があったようです)
運用上はログに出しているcursorをコード上($cursor)にセットし直し、15分ごとに流して制限回避しました。
*/

$consumer_key = "";
$consumer_secret = "";
$access_token = "";
$access_secret = "";

$Bot = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_secret);

$cursor = "-1";
for($i=0; $i<15; $i++) {
	$timeline = $Bot->get("friends/list", array("screen_name" => "target_user", "count" => "200", "cursor" => $cursor));
	$cursor = $timeline->next_cursor;

	foreach($timeline->users as $status){
     		$screen_name = $status->screen_name; // ユーザーID
		//$name = $status->name; // ユーザー名
  		print_r($screen_name."\n");   
	}
	print_r("CURSOR::".$cursor."\n");
	if($cursor == 0) {
		print_r("END");
		break;
	}
}

?>
