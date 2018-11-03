<?php

require_once("models/Post.class.php");
require_once("models/Event.class.php");

if(!$USER_VALID)
    Utils::finalResponse(["message"=>"You must be logged in", "status"=>false]);

if(!isset($_POST['post']) || !intval($_POST['post']))
    Utils::finalResponse(["message"=>"Invalid request", "status"=>false]);

if(!$post = Post::get("id, user")->where("id={$_POST['post']}")->send())
    Utils::finalResponse(["message"=>"No such post", "status"=>false]);

$event = Event::get()->where("post={$post->id} AND acting_user={$CURRENT_USER->id}")->send();
if(!$event){
    $event = new Event();
    $event->post = $post->id;
    $event->acting_user = $CURRENT_USER->id;
    $event->action="like";
    $event->insert()->send();
    if($post->user->recieve_updates)
        Utils::sendEmail($post->user->email, "{$CURRENT_USER->uname} Just liked one of your posts!!!", "Make it rain!");
}else{
    $event->delete()->send();
    $event = null;
}
Utils::finalResponse(["value"=>$post->getLikes(), "status"=>true]);

?>