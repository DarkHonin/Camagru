<?php

$INVALID_REQUEST = ["message"=>"invalid request", "status"=>false];
$TRUE_REQUEST  = ["status"=>true];
$FALSE_REQUEST = ["status"=>false];
$LOGIN_REQUEST = ["message"=>"You need to be logged in to do this", "status"=>false];

function VALUE_RESPONSE($value, $state){
	return ["data"=>$value, "status"=>$state];
}

function check_data($keys, &$test){
	if(!is_array($test))
		Utils::finalResponse($INVALID_REQUEST);
	foreach($keys as $k){
		if(!isset($test[$k]) || empty($test[$k]))
			Utils::finalResponse($INVALID_REQUEST);
		$test[$k] = htmlentities($test[$k]); 
	}
}
$payload = $_POST;
check_data(["data", "request", "type"], $payload);
include_once("request/request.php");

?>