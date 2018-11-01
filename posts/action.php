<?php

$payload = $_POST;

if(!isset($payload["action"]))
    Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);

require_once("models/User.class.php");
if(!User::valid())
    Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);


?>