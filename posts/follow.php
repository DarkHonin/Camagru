<?php

require_once("models/Event.class.php");
require_once("models/User.class.php");

if(!isset($_POST['user']) || !intval($_POST['user']))
    Utils::finalResponse(["message"=>"Invalid request", "status"=>false]);

if($_POST['user'] == $CURRENT_USER->id)
    Utils::finalResponse(["message"=>"You cant follow yourself", "status"=>false]);

if(!$user = User::get("id, email, recieve_updates")->where("id={$_POST['user']}")->send())
    Utils::finalResponse(["message"=>"No such user", "status"=>false]);

$event = Event::get()->where("post={$_POST['user']} AND acting_user={$CURRENT_USER->id}")->send();
if(!$event){
    $event = new Event();
    $event->post = $_POST['user'];
    $event->acting_user = $CURRENT_USER->id;
    $event->action = "follow";
    $event->insert()->send();
    if($user->recieve_updates)
        Utils::sendEmail($user->email, "{$CURRENT_USER->uname} Has decided to stalk you!!!", "Are you ok with this?");
}else{
    $event->delete()->send();
    $event = null;
}
Utils::finalResponse(["value"=>($event? "Unfollow":"Follow"), "status"=>true]);

?>