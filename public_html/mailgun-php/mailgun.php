<?php
require './vendor/autoload.php';
use Mailgun\Mailgun;

# First, instantiate the SDK with your API credentials
$mg = Mailgun::create('0759203637d7a344cc5104e4b07100c9-dc5f81da-71a0b2b2');
$domain = "mushroomdomecabin.com";

$webmaster_email = "kmrache@rocketmail.com";

$feedback_page = "../index.html";
$error_page = "../error_message.html";
$thankyou_page = "../thank_you.html";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
*/
$email_address = $_REQUEST['email_address'] ;
$message = $_REQUEST['message'] ;
$first_name = $_REQUEST['first_name'] ;
$last_name = $_REQUEST['last_name'] ;


// Now, compose and send your message.
// $mg->messages()->send($domain, $params);
//$parameters = [
//    'from'    => "contact@jordanthewebdesigner",
//    'to'      => "jordanthewebdesigner@gmail.com",
//    'subject' => 'Web Design Inquiry',
//    'text'    => "message"
//];
//
//$mg->messages()->send($domain,  [
//    'from'    => "contact@jordanthewebdesigner",
//    'to'      => "jordanthewebdesigner@gmail.com",
//    'subject' => 'Web Design Inquiry',
//    'text'    => "message"
//]);
/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
*/
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['email_address'])) {
header( "Location: $feedback_page" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($first_name) || empty($email_address) || empty($message)) {
header( "Location: $error_page" );
}

/* 
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
elseif ( isInjected($email_address) || isInjected($first_name)  || isInjected($last_name) ) {
header( "Location: $error_page" );
}



// If we passed all previous tests, send the email then redirect to the thank you page.
else {
			if ( filter_var($email_address, FILTER_VALIDATE_EMAIL) ) {
$mg->messages()->send($domain, [
    'from'    => "Contact Form <contact@mushroomdomecabin.com>",
    'to'      => $webmaster_email,
    'subject' => "Mushroom Dome Cabin Inquiry",
    'text'    => $message."\n\n".$first_name." ".$last_name."\n".$email_address
]);
    header( "Location: $thankyou_page" );

			}

}
?>