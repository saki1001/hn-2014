<?php
session_start();
require_once('twitteroauth/twitteroauth/twitteroauth.php'); //Path to twitteroauth library
 
$twitteruser = "HardNips_band";
$notweets = 10;
$consumerkey = "ptMf7NKWaD2F0bEyUPQlQ";
$consumersecret = "eiGwQR3OiJIbYPuLe1VHTb37rWpEPAJkJSmUg3ug";
$accesstoken = "593190524-Tj5Zn4Lmmi5QtdhxuV0VjofBJ00J3HGS9aVaB2gA";
$accesstokensecret = "XtjKIqnejU2KaCf1i7zI0okT4vCIEppg8GKYrYvSimc";
 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
  
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
 
echo "callback(". json_encode($tweets) .")";
?>