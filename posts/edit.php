<?php

require_once("parts/forms/Comment.form.php");
require_once("models/User.class.php");
require_once("models/Post.class.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new CommentFrom($post->description, "Describe your post", 0);

$payload = $_POST;

$errors = [];
if(!$builder->valid($frm, $payload, $errors))
    Utils::finalResponse(["message"=>"invalid request", "status"=>false]);

if($err = User::verify()){
    include_once("page/404.php");
    return;
}

$item = Post::get("id")->where("id={$payload['post']}")->send();
if(is_array($item) || empty($item))
    Utils::finalResponse(["message"=>"invalid request", "status"=>false]);

$item->offset = $payload['image'];

$item->update()->where("id={$payload['post']}")->send();

?>