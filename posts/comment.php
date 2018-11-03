<?php
require_once("models/User.class.php");
require_once("models/Post.class.php");
require_once("models/Comment.class.php");
if(!$USER_VALID){
    Utils::finalResponse(["message"=>"invalid request", "status"=>false]);
}

require_once("parts/forms/Comment.form.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new CommentFrom("", "Write a comment", 0);

$payload = $_POST;

$error = [];
$builder->valid($frm, $payload, $error);

if(!empty($error))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);

$post = Post::get("id, user")->where("id={$payload['post']}")->send();
if(!$post)
	Utils::finalResponse(["message"=>"Inavlid request", "status"=>false]);

$comment = new Comment();

$comment->user = $_SESSION['user']['id'];
$comment->comment = $payload['description'];
$comment->post = $post->id;

$comment->insert()->send();
if($post->user->recieve_updates)
	Utils::sendEmail($post->user->email, "{$CURRENT_USER->uname} Just commented: \n\n \"{$comment->comment}\"\n\n on one of your posts", "A comment");

Utils::finalResponse(["message"=>"Your comment has been posted", "redirect"=>"/post/$post->id", "status"=>true]);

?>