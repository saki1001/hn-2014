<?php
//requiring FB PHP SDK
require 'fb-sdk/src/facebook.php';

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

/* make the API call */
FB.api(
    "/204470146095/events",
    function (response) {
      console.log(response);
      if (response && !response.error) {
        /* handle the result */
      }
    }
);

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