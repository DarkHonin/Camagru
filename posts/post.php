<?php

require_once("models/User.class.php");
if(!$USER_VALID){
    Utils::finalResponse(["message"=>"Inavlid request, login required", "status"=>false]);
	return;
}

require_once("models/Post.class.php");

if(!$pid = intval($nav[1])){
    Utils::finalResponse(["message"=>"Inavlid request, inavlid post id", "status"=>false]);
    return;
}

if(!$post = Post::get()->where("id=$pid")->send()){
    include_once("parts/404.php");
    return;
}

if($post->user->id !== $CURRENT_USER->id){
    Utils::finalResponse(["message"=>"Inavlid request, not your post", "status"=>false]);
}

if(isset($nav[2]) && $nav[2] == "edit"){
    include_once("edit.php");
    return;
}

require_once("parts/forms/EditPost.form.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new EditPostForm($post->description);

$payload = $_POST;
$err= [];
if(!$builder->valid($frm, $payload, $err))
    Utils::finalResponse(["data"=>["error"=>$err], "status"=>false]);

$post->description = htmlentities($payload["decription"]);
unset($post->user);
$post->update()->where("id=".$post->id)->send();
include_once("src/render.php");
Utils::finalResponse(["message"=>"Posted!", "redirect"=>"/user/{$CURRENT_USER->uname}", "status"=>true]);
?>