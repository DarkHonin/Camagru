<?php

require_once("models/User.class.php");
if($err = User::verify()){
    include_once("page/logout.php");
    Utils::finalResponse(["data"=>["global"=>"Inavlid request"], "status"=>false]);
	return;
}

require_once("models/Post.class.php");

if(!$pid = intval($nav[1])){
    Utils::finalResponse(["data"=>["global"=>"Inavlid request"], "status"=>false]);
    return;
}

if(!$post = Post::get("id, user, description")->where("id=$pid")->send()){
    include_once("parts/404.php");
    return;
}

if($post->user->session_token !== $_SESSION['user']['session_token']){
    include_once("page/logout.php");
    Utils::finalResponse(["data"=>["global"=>"Inavlid request"], "status"=>false]);
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
Utils::finalResponse(["message"=>"Info updated.", "data"=>["redirect" => "/post/{$post->id}"],"status"=>true]);
?>