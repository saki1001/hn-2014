<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Hard Nips Facebook Events feed</title>

</head>

<body>
<?php

// include required files form Facebook SDK 
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
 
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
 
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );
 
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
 
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
 
// start session
session_start();
 
// init app with app id and secret
// FacebookSession::setDefaultApplication( '1428096120773288','2fd26674a216fa59e76fa759f3ddd708' );
// Hard Nips account App
FacebookSession::setDefaultApplication( '160660020686','699ec8b7822b68cea5c4260ba32abaf9' );

// If you already have a valid access token:
// Hard Nips App (via personal access)
$session = new FacebookSession('CAAAAJWgVXc4BAPG2gH9R1C26fEoZCVmblKLB6Xj182mjzJWbT2j7lNIQ5955SYqEdn9I2aJQBHk5KZBXf8EIgBHNaL89WvayFZC3ZAGSf6vZBklllmKC6uCzNkAH75GXwRPDXg05iLv06fZCumYGMrfYiM1odpKiHGtySTZAHjh44GHI2FPl5u8ZAgifm7s6z8YTEvrpjcg01JtcT0mIizx8');

// Get access token
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject()->asArray();

// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://hardnipsbrooklyn.com/' );
//
// see if a existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // create new session from saved access_token
  $session = new FacebookSession( $_SESSION['fb_token'] );

  // validate the access_token to make sure it's still valid
  try {
    if ( !$session->validate() ) {
      $session = null;
    }
  } catch ( Exception $e ) {
    // catch any exceptions
    $session = null;
  }
}

if ( !isset( $session ) || $session === null ) {
  // no session exists

  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
    // handle this better in production code
    print_r( $ex );
  } catch( Exception $ex ) {
    // When validation fails or other local issues
    // handle this better in production code
    print_r( $ex );
  }

}

// see if we have a session
if ( isset( $session ) ) {

  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );

  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/204470146095/events' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject()->asArray();

  // print profile data
  // print_r( $graphObject['data']);
  $data = $graphObject['data'];

  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  // echo '<a href="' . $helper->getLogoutUrl( $session, 'http://yourwebsite.com/app/logout.php' ) . '">Logout</a>';

} else {
  // show login url
  echo 'Error: No token.';
  // echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends' ) ) . '">Login</a>';
}

$fqlResult = $data;

// //requiring FB PHP SDK
// require 'fb-sdk/src/facebook.php';
//
// //initializing keys
// // Hard Nips Website
// // App ID created 11/15/2014
// $facebook = new Facebook(array(
//   'appId'  => '1428096120773288',
//   'secret' => '2fd26674a216fa59e76fa759f3ddd708',
//   'cookie' => true // enable optional cookie support
// ));
//
// //query the events
// //currently selecting name, start_time, end_time, location, description this time
// //but there's other data that you can get from the event table (https://developers.facebook.com/docs/reference/fql/event/)
// $fql    =   "SELECT eid, name, start_time, end_time, location, description
//       FROM event WHERE eid IN ( SELECT eid FROM event_member WHERE uid = 204470146095 ) AND start_time >= now()
//       ORDER BY start_time asc"; // add LIMIT 0,3"; to only display 3 events
//
// $param  =   array(
// 'method'    => 'fql.query',
// 'query'     => $fql,
// 'callback'  => ''
// );
//
// $fqlResult   =   $facebook->api($param);

if(empty($fqlResult)) {
	echo "<h3 class='no-dates'>Oh no, Nips do not have show at this time :(</h3> <p>Follow us <a href='http://www.tumblr.com/follow/hardnipsband' title='Follow Hard Nips on Tumblr' target='_blank'>here on Tumblr</a>, or <a href='http://www.facebook.com/pages/HARD-NIPS/204470146095' title='Hard Nips on Facebook' target='_blank'>Facebook</a> and <a href='http://www.twitter.com/HardNips_band' title='Hard Nips on Twitter' target='_blank'>Twitter</a> for updates.</p>";	
}

//looping through retrieved data
else {
	foreach( $fqlResult as $keys => $values ){

	//getting 'start' and 'end' date,
	$start_date = date ( 'M d', strtotime($values->start_time) );
	$end_date = date ( 'M d', strtotime($values->end_time) );
	
	// Added the new date structure using strtotime above ^ when all the dates started displaying as Dec 31 1969
	// replacing these two lines... 
	//$start_date = date ( 'M d Y', $values [ 'start_time' ] );
	//$end_date = date ( 'M d Y', $values [ 'end_time' ] );

	//printing the data
		echo "<a href='https://www.facebook.com/events/{$values->id}' title='RSVP on the Facebook Event page' target='_blank'><div class='event-container fade'>";

		if( $start_date == $end_date ){
			//if $start_date and $end_date is the same
			//it means the event will happen on the same day
			echo "<div class='date'><p>{$start_date}</p></div>";
		}
		
		else{
			//else if $start_date and $end_date is NOT the equal
			//it means that the event will be
			//extended to another day
			// I removed this and added the next line: echo "<span>{$start_date} to {$end_date}</span>";
			echo "<div class='date'><p>{$start_date}</p></div>";
		}

		echo "<div class='details'><h3>{$values->name}</h3>
		<p class='rsvp'>RSVP on Facebook</p>
		</div>";
	echo "</div></a>";
}
}
?>
</body>
</html>