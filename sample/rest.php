<?php

/*

 This is an example intended to show how to access the Lotame ReST API.

 We use simple classes available in the PHP 5.3.6 distro for simplicity

 There may be more efficient ways to parse the XML and JSON, especially using other
 libraries, but that is not the focus of the example.

 This example also does not include an error handling - You should
 make sure that you do include error handling with any code, but especially
 when working with an external API!
*/

// make sure you install php5-curl and libxml

// just for kicks, let's time the whole thing
$startTime = microtime(true);

// Set up our username and password
$username = 'YOUR USERNAME/EMAIL GOES HERE';
$password = 'YOUR PASSWORD GOES HERE';

// set up our request to get the token
$restUrl = 'https://api.lotame.com/';

// urlencode the post args
$postargs = 'email='.urlencode($username).'&password='.urlencode($password);

// initialize the curl session
$session = curl_init($restUrl);

// set up our curl options for posting the args
curl_setopt($session, CURLOPT_POST, true);
curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// run curl, get the token
$token = curl_exec($session);
curl_close($session);

// You can see the form of the token here
echo ("our token is $token\n");

// Now that we have a token, we can get some data!

/*
  Set the restUrl to the request we want to make - in this case we are calling the
  behaviorservice and asking it about a particular behavior, 5990
*/
$restUrl = 'https://api.lotame.com/behaviorservice/behaviors/behavior/5990';

$session = curl_init($restUrl);


// Add our new token to the header
curl_setopt($session,CURLOPT_HTTPHEADER,array("Authorization: $token"));
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make our request
$xmlResponse = curl_exec($session);

// close the session
curl_close($session);

// You can see the xml we get back
echo ("\n---- showing behavior xml ----\n");
echo ("$xmlResponse \n");


// Now let's parse the XML and get back just the values we want to this behavior.
$xmlBehaviors = simplexml_load_string($xmlResponse);

// You can take a look at the structure of the SimpleXMLObject if you like .....
print_r($xmlBehaviors);
echo("\n");

// and print out the results, we don't need to loop through anything, because we only have one behavior
echo ("name = {$xmlBehaviors->attributes()->name} \n");
echo ("id = {$xmlBehaviors->attributes()->id} \n");

/*
  Now lets make a request to the audience service to get a list of audiences
  If you don't pass any parameters, you will get back all of the audiences
  that you are able to view in the system.
  If you have multiple client, you could use a parameter of client_id to restrict the audiences
  that are going to show up, like this:
     restUrl = 'https://api.lotame.com/as/audiences?client_id=CLIENT ID GOES HERE'
*/

// set our REST url to get what we want, in this case an audience list
$restUrl = "https://api.lotame.com/as/audiences";

// Initiate the session with the new end point
$session = curl_init($restUrl);

// Add the token to the header
curl_setopt($session,CURLOPT_HTTPHEADER,array("Authorization: $token"));
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// Make the request
$xmlResponse = curl_exec($session);

// You can see the xml we get back
####echo ("\n---- showing audience xml ----\n");
####echo ($xmlResponse);


####echo ("\nlet's parse the xml to get the name and id ...\n");

// Now let's parse the XML and get back just the values we want.
$xmlAudienceList = simplexml_load_string($xmlResponse);

/* 
	We have an audience collection, let's get all of the audiences .. 
 	make sure to include the namespace - http://services.lotame.com/cc/audienceservice
*/
$audiences = $xmlAudienceList->children('http://services.lotame.com/cc/audienceservice');

// for each audience, get the name and id and put it in an array
$audiencesArray = array();

foreach($audiences->Audience as $audience )
{
	$audiencesArray["{$audience->id}"] = "{$audience->name}";	
}

// show the array
print_r($audiencesArray);

// clear our array
unset($audiencesArray);

// But, what if we wanted to use JSON instead of XML?

//first let's update the header
curl_setopt($session,CURLOPT_HTTPHEADER,array("Authorization: $token","Accept: application/json"));
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

//Make our request
$jsonResponse = curl_exec($session);

// You can see the JSON we get back
echo ("\n---- showing JSON ----\n");
echo ("$jsonResponse \n");


/* 
  Lets's decode our string and then loop through, getting our
  audiences.  We are treating everything as an array.
  If we had wanted to work with objects instead we would use

	$jsonAudienceList = json_decode($jsonResponse);

  instead and then loop through objects instead of arrays.
*/
$jsonAudienceList = json_decode($jsonResponse, true);

// Not exactly a "pretty print" or our JSON, but close enough
echo ("\n---- showing 'pretty' JSON ----\n");
print_r ($jsonAudienceList);

// We are creating an array of audiences that looks like this: [Audience ID]=> Audience Name
foreach( $jsonAudienceList["Audience"] as $aud)
{
	$audiencesArray[$aud["id"]] = ($aud["name"]);

}

// Well, we have it now, but let's prove it by printing it all out.
echo ("\n---- showing parsed JSON ----\n");
print_r($audiencesArray);

// close the session
curl_close($session);

// We were just timing for fun, let's see how long it took
$totalTime = microtime(true) - $startTime;
echo ("\nTotal time to complete: $totalTime seconds\n");
