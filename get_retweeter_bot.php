<?php
require_once("./twitteroauth-master/autoload.php");
require_once("./twitteroauth-master/src/TwitterOAuth.php");
use Abraham\TwitterOAuth\TwitterOAuth;

/*
あるアカウントの特定の文言を含むTweetをRTしたユーザを検索し、リストに追加してリプライを送信します。
鍵付きアカウントは対象外です。
定期的に実行することである程度リアルタイム性を持たせることが可能です。
*/

$consumer_key = "";
$consumer_secret = "";
$access_token = "";
$access_secret = "";

$user = "target_user";

// 前回最終のsince_idを読み込み
$fp = fopen("last_id.txt", "r");
$since_id = fgets($fp);
fclose($fp);
print_r("since_id :".$since_id."\n");

//タイムラインの情報をゲット
$Bot = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_secret);
$timeline = $Bot->get("search/tweets", array("q" => "#キャンペーン filter:retweets @target_user", "since_id" => $since_id, "count" => "100"));

$applying_users = "";
$succeed_to_send_users = "";
$failed_to_send_users = "";

foreach($timeline->statuses as $status){
    $screen_name = $status->user->screen_name; // ユーザーID
    // 呟き内容。余分なスペースを消して、半角カナを全角カナに、全角英数を半角英数に変換
    $text = mb_convert_kana(trim($status->text),"rnKHV","utf-8"); 
    print_r("\nNAME\t\t".$screen_name."\nCONTENT\t".$text."\n");
    $applying_users .= $screen_name."\n";

	// 返信を投稿
	$tx = "応募完了";
	print_r("REPLY\t\t".$tx."\n");
	$post_statues = $Bot->post("statuses/update", ["status" => "@".$screen_name." ".$tx]);

	if($post_statues->id_str) {
		$succeed_to_send_users .= $screen_name."\n";
	} else {
		// 投稿失敗した場合
		$failed_to_send_users .= $screen_name."\n";
	}
}

// 保存
if(count($timeline->statuses) > 0) {
	$new_since_id = $timeline->statuses[0]->id_str;
	print_r("new since id :");
	print_r($new_since_id);
	$fp = fopen("last_id.txt", "w");
	fwrite($fp, $new_since_id);
	fclose($fp);

	print_r("\n@@applying users@@\n");
	print_r($applying_users);
	$fp = fopen("applying_users.txt", "a");
	fwrite($fp, $applying_users);
	fclose($fp);

	print_r("\n@@succeed to send users@@\n");
	print_r($succeed_to_send_users);
	$fp = fopen("succeed_to_send_users.txt", "a");
	fwrite($fp, $succeed_to_send_users);
	fclose($fp);

	print_r("\n@@failed to send users@@\n");
	print_r($failed_to_send_users);
	$fp = fopen("failed_to_send_users.txt", "a");
	fwrite($fp, $failed_to_send_users);
	fclose($fp);
} else {
	print_r("--- no post ---\n");
}

?>
