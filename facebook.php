<?php
	require_once dirname(__FILE__) . '/include/facebook.php';

function trim_text($input, $length, $ellipses = true, $strip_html = true) {
	//strip tags, if desired
	if ($strip_html) {
			$input = strip_tags($input);
	}

	//no need to trim, already shorter than trim length
	if (strlen($input) <= $length) {
			return $input;
	}

	//find last space within length
	$last_space = strrpos(substr($input, 0, $length), ' ');
	$trimmed_text = substr($input, 0, $last_space);

	//add ellipses (...)
	if ($ellipses) {
			$trimmed_text .= '...';
	}

	return $trimmed_text;
}

$text = "_YOUR TEXT_";
$toFB = strip_tags(html_entity_decode($text)); //If $text have some HTML tags inside
$delkaTextu = strlen($toFB); //Plaintext length
$toFBfinal = trim_text($page["text"], $delkaTextu/2); //Trimed text on half

//Add to FB page
$fb = new Facebook([
				'appId' => '_APPID_',
				'secret' => '_APPSECRET_'
				]);

if($fb->getUser() == 0){
	$loginUrl = $fb->getLoginUrl(array(
		scope => 'manage_pages,publish_actions' //You need minimal these scopes for publis on your page
	));

	echo "<a href='$loginUrl'>Login with Facebook</a>";
}else{
	$pages = $fb->api('me/accounts');

	$id = $pages[data][0][id];
	$token = $pages[data][0][access_token];

	$api = $fb->api($id . '/feed', 'POST', array(
		access_token => $token,
		link => '_YOUR WABPAGE LINK_',
		message => $toFBfinal,
		name => '_NAME OF LINK_',
		picture => '_PATH TO YOUR IMAGE_'
	));
}
if($api["id"]){
	echo "ALL IS OK";
}