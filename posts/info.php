<?php

if(!isset($nav[1]) || (!isset($_POST['item']) || empty($_POST['item'])))
    Utils::finalResponse(["status"=>false]);

switch($nav[1]){
    case "uname_available":
        require_once("models/User.class.php");
        $users = User::get("uname")->where("uname='{$_POST['item']}'")->send();
        if(empty($users))
            Utils::finalResponse(["status"=>true]);
        Utils::finalResponse(["status"=>false]);
    case "email_available":
        require_once("models/User.class.php");
        $users = User::get("email")->where("email='{$_POST['item']}'")->send();
        if(empty($users))
            Utils::finalResponse(["status"=>true]);
        Utils::finalResponse(["status"=>false]);
}


?>