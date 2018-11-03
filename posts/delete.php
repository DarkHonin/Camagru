<?php

require_once("models/Post.class.php");

if(!$USER_VALID)
    Utils::finalResponse(["message"=>"You must be logged in", "status"=>false]);

if(!isset($_POST['post']) || !intval($_POST['post']))
    Utils::finalResponse(["message"=>"Invalid request", "status"=>false]);

if(!$post = Post::get("id, user")->where("id={$_POST['post']}")->send())
    Utils::finalResponse(["message"=>"No such post", "status"=>false]);

if($post->user->id !== $CURRENT_USER->id)
    Utils::finalResponse(["message"=>"Invalid request", "status"=>false]);

$post->delete()->send();
Utils::finalResponse(["message"=>"Post has been deleted", "status"=>true]);
?>
