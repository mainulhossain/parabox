<?php
    require 'facebook.php';

    $fbconfig['appid' ] = "207730709270457";
    $fbconfig['secret'] = "2f5515de45d6101b186ad63d11071f3d";

//    $fbconfig['baseUrl']    =   "http://parabox.keensocial.com/survey.php";// "http://thinkdiff.net/demo/newfbconnect1/iframe/sdk3";
    $fbconfig['appBaseUrl'] =   "http://apps.facebook.com/paranoidbox/";// "http://apps.facebook.com/thinkdiffdemo";

//Create facebook application instance.
$facebook = new Facebook(array(
  'appId'  => $fbconfig['appid' ],
  'secret' => $fbconfig['secret'],
  'cookie' => true,
));

$friends = array();
$sent = false;
$userData = null;

//redirect to facebook page
if(isset($_GET['code'])){
	header("Location: " . $fbconfig['appBaseUrl']);
	exit;
}

$user = $facebook->getUser();
if ($user) {
	//get user data
	try {
		$userData = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		//do something about it
	}
	
	//get 5 random friends
	try {
		$friendsTmp = $facebook->api('/' . $userData['id'] . '/friends');
		shuffle($friendsTmp['data']);
		array_splice($friendsTmp['data'], 5);
		$friends = $friendsTmp['data'];
	} catch (FacebookApiException $e) {
		//do something about it
	}
	
	//post message to wall if it is sent trough form
	if(isset($_POST['mapp_message'])){
		try {
			$facebook->api('/me/feed', 'POST', array(
				'message' => $_POST['mapp_message']
			));
			$sent = true;
		} catch (FacebookApiException $e) {
			//do something about it
		}
	}
	
} else {
	$loginUrl = $facebook->getLoginUrl(array(
		'canvas' => 1,
		'fbconnect' => 0,
		'scope' => 'publish_stream',
	));
}
?>
