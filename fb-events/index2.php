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
FacebookSession::setDefaultApplication( '1428096120773288','2fd26674a216fa59e76fa759f3ddd708' );
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://hardnipsbrooklyn.com/' );
 
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
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject()->asArray();
  
  // print profile data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
  
  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  echo '<a href="' . $helper->getLogoutUrl( $session, 'http://yourwebsite.com/app/logout.php' ) . '">Logout</a>';
  
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends' ) ) . '">Login</a>';
}

// Using SDK 
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

if($session) {

  try {

    $user_profile = (new FacebookRequest(
      $session, 'GET', '/204470146095'
    ))->execute()->getGraphObject(GraphUser::className());

    echo "Name: " . $user_profile->getName();

  } catch(FacebookRequestException $e) {

    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();

  }   

}

$fqlResult = '';

if(empty($fqlResult)) {
	echo "<p></p>";	
}

//looping through retrieved data
else {

	foreach( $fqlResult as $keys => $values ){

	//getting 'start' and 'end' date,
	$start_date = date ( 'M d', strtotime($values['start_time']) );
	$end_date = date ( 'M d', strtotime($values['end_time']) );
	
	// Added the new date structure using strtotime above ^ when all the dates started displaying as Dec 31 1969
	// replacing these two lines... 
	//$start_date = date ( 'M d Y', $values [ 'start_time' ] );
	//$end_date = date ( 'M d Y', $values [ 'end_time' ] );

	//printing the data
		echo "<li>";

		if( $start_date == $end_date ){
			//if $start_date and $end_date is the same
			//it means the event will happen on the same day
			echo "<span class='date'>{$start_date} </span>";
		}
		
		else{
			//else if $start_date and $end_date is NOT the equal
			//it means that the event will be
			//extended to another day
			// I removed this and added the next line: echo "<span>{$start_date} to {$end_date}</span>";
			echo "<span class='date'>{$start_date} </span>";
		}

		echo "<span class='details'>{$values['name']}</span>";
		// echo "<p class='note'>" . $values['description'] . "</p>";
	echo "</li>";
}
}
?>