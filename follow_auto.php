<?php
require_once("./twitteroauth-master/autoload.php");
require_once("./twitteroauth-master/src/TwitterOAuth.php");
use Abraham\TwitterOAuth\TwitterOAuth;

/*
リストに記載されたユーザ一覧から一括フォローします。
*/

$consumer_key = "";
$consumer_secret = "";
$access_token = "";
$access_secret = "";

$file = fopen("succeed_to_send_users.txt", "r");
$Bot = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_secret);
$ng_users = "";

while (!feof($file)) {
	$user = fgets($file);
	$statues = $Bot->post("friendships/create", array("screen_name" => $user, "follow" => false));
	//print_r($statues);
	if($statues->id_str) {
		print_r($user);
	}else{
		$ng_users .= $user."\n";
	}
}

fclose($file);
echo("###### NG user ######\n");
print_r($ng_users);
?>
