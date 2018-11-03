<?php




$redirect = [
	"info" => __DIR__."/info/request.php",
	"update" => __DIR__."/update/request.php"
];

if(!isset($redirect[$payload['type']]))
	Utils::finalResponse($INVALID_REQUEST);

$path = $redirect[$payload['type']];

if(!file_exists($path))
	Utils::finalResponse($INVALID_REQUEST);

include_once($path);


?>