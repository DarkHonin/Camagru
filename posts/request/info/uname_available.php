<?php
require_once("models/User.class.php");
$users = User::get("uname")->where("uname='{$payload['data']}'")->send();
if(empty($users))
	Utils::finalResponse($TRUE_REQUEST);
Utils::finalResponse($FALSE_REQUEST);
?>