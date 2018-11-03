<?php

require_once("models/Post.class.php");

check_data(["comment", "post", "csrf"], $payload['data']);

$postid = $payload['data']['post'];
$comment = $payload['data']['comment'];

if(!check_csrf_token($_SESSION['user']['uname']."_comment", $payload['data']['csrf']))
	Utils::finalResponse($INVALID_REQUEST);

$post = Post::get("id, user")->where("id=".$postid)->send();
if(!$post || empty($post))
	Utils::finalResponse(["message"=>"Post does not exist", "status"=>false]);

$acting_user = $_SESSION['user']['id'];
$subject_user = $post->user->id;

require_once("models/Comment.class.php");

$comment = new Comment();
$comment->parseArray([
	"user"=>$acting_user,
	"post"=>$postid,
	"comment"=>$comment
]);

$user = User::get("email")->where("id=$subject_user")->send();

if(!$user || empty($user))
	Utils::finalResponse(["message"=>"An error ocurred", "status"=>false]);
Utils::sendEmail($user->email, "Your post was just liked by: ".$_SESSION['user']['uname'], "A like my friend");
$event = new Event();
$event->parseArray([
	"post" => $postid,
	"acting_user"=>$acting_user,
	"action"=>"like"
]);
$event->insert()->send();
Utils::finalResponse(["message"=>"Liked", "status"=>true]);


?>