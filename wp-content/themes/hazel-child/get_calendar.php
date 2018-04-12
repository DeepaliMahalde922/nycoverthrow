<?php 
/*
Template Name: Get Calendar
*/

include(__DIR__.'/google-api-php-client/src/Google/autoload.php');

/**
 * Tell google what we're doing
 */
$client = new Google_Client();
$client->setApplicationName('OVERTHROW TRAINER NAMES');
$client->setDeveloperKey('AIzaSyBqEOi10v3_Z24QKDZkfWrAUGMNqvSioOE');
$cal = new Google_Service_Calendar($client);

/**
 * The calendar id, found in calendar settings. if your calendar is through google apps
 * must have all events viewable in sharing settings.
 */
$calendarId = 'overthrownewyork@gmail.com';

/**
 * This is where we actually put the results into a var
 */
$params = array(
	'timeMin' => '2015-7-18T00:00:59+00:00',
	'maxResults' => 50
);
$events = $cal->events->listEvents($calendarId, $params);

$calTimeZone = $events->timeZone; //get the tz of the calendar

//Set the default timezone so php doesn't complain. set to your local time zone.
date_default_timezone_set($calTimeZone);

/**
 * Start the loop to list events
 */
foreach ($events->getItems() as $event) {
	$eventDateStr = $event->start->dateTime; //Convert date to month and day

	if( empty($eventDateStr) ){
		$eventDateStr = $event->start->date;
		$eventTimeStr = $event->start->time;
	}

	$temp_timezone = $event->start->timeZone;

	//This overrides the calendar timezone if the event has a special tz
	if (!empty($temp_timezone)) {
		$timezone = new DateTimeZone($temp_timezone); //Get the time zone
	} else {
		$timezone = new DateTimeZone($calTimeZone);
	}

	$eventdate = new DateTime($eventDateStr,$timezone);

	$newtime = $eventdate->format('H:i');
	$newday = $eventdate->format('j');
	$newmonth = $eventdate->format('M');
	$newyear = $eventdate->format('Y');

	echo $newtime.'#'.$newday.'-'.$newmonth.'-'.$newyear.'@';
}
?>