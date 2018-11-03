<?php

if($err = User::verify())
    Utils::finalResponse($LOGIN_REQUEST);

$redirect = [
	"like"				=> __DIR__."/like.php"
];

$path = $redirect[$payload['request']];

if(!file_exists($path))
	Utils::finalResponse($INVALID_REQUEST);

include_once($path);
?>