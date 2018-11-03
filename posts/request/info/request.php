<?php

if(!isset($payload['request']))
	Utils::finalResponse($INVALID_REQUEST);

$redirect = [
	"uname_available" 	=> __DIR__."/uname_available.php",
	"email_available" 	=> __DIR__."/email_available.php",
	"likes"				=> __DIR__."/likes.php"
];

if(!isset($redirect[$payload['request']]))
	Utils::finalResponse($INVALID_REQUEST);

$path = $redirect[$payload['request']];

if(!file_exists($path))
	Utils::finalResponse($INVALID_REQUEST);

include_once($path);
?>