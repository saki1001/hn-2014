<?php
session_start();
require_once('twitteroauth/twitteroauth/twitteroauth.php'); //Path to twitteroauth library
 
// Created 11/15/2013 farawaybeaches account
$twitteruser = "HardNips_band";
$notweets = 10;
$consumerkey = "hahkWGMU9rkA16ihElVLApL74";
$consumersecret = "frt9IfXgRF2wfBleDROYUt6OaFSOCV68E8zvnK7Ou8zFZ8ONaF";
$accesstoken = "1356308496-D22pnX34flWUpnDJRkR3g0eGVTneZjdzxdZ55NN";
$accesstokensecret = "gxUq3mpGS6vUUBKnPxosBtLClFKERKtsY12QHmH486Yrz";
 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
  
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
 
echo "callback(". json_encode($tweets) .")";
?>