<?php
/**
* Resources
* - @j7mbo: https://github.com/J7mbo/twitter-api-php
* - Ian Anderson Gray: http://iag.me/socialmedia/build-your-first-twitter-app-using-php-in-8-easy-steps/
**/

ini_set('display_errors', 1);

date_default_timezone_set('America/Los_Angeles');

require_once('TwitterAPIExchange.php');
require_once('tokens.php');

/** Perform a GET request **/
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';
$getfield = '?q=from%3Aalisonleemckay%20%23donutsforever';
$twitter = new TwitterAPIExchange($settings);

/** Decode the JSON feed **/
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}

/** Parse the JSON feed and retrieve most recent donut post **/
$count = 0;
foreach($string as $item) {
	if($count < 1){
		// Get time of most recent tweet with donut hashtag
		$time = $item[0][created_at];

		// Set up Twitter time for reformatting for CountUp script
		$newTimeObject = new DateTime($time);

		// Format time for CountUp script
		$newTimeFormat = $newTimeObject->format('F d, Y H:i:s');

		// Set new time to correct time zone
		$newTimeZone = date_create($newTimeFormat, timezone_open('Europe/London'));	
		date_timezone_set($newTimeZone, timezone_open('America/Los_Angeles'));

		$count++;
	}
}

?>
