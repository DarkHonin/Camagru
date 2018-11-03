<?php

require_once("parts/forms/Comment.form.php");
require_once("models/User.class.php");
require_once("models/Post.class.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new CommentFrom($post->description, "Describe your post", $post->id);

$payload = $_POST;

$errors = [];
if(!$builder->valid($frm, $payload, $errors))
    Utils::finalResponse(["message"=>"invalid request", "data"=>$errors, "status"=>false]);

$post->overlay = json_encode(json_decode($payload['image'], true)['stickers']);

$post->update()->where("id={$payload['post']}")->send();
include_once("src/render.php");
Utils::finalResponse(["message"=>"Update ok", "redirect"=>"/user/{$CURRENT_USER->uname}", "status"=>true]);

?>