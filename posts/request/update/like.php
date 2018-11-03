<?php
require_once("models/Post.class.php");

check_data(["post", "csrf"], $payload['data']);

if(!check_csrf_token($_SESSION['user']['uname']."_like", $payload['data']['csrf']))
	Utils::finalResponse($INVALID_REQUEST);

$postid = $payload['data']['post'];
$post = Post::get("id, user")->where("id=".$postid)->send();
if(!$post || empty($post))
	Utils::finalResponse(["message"=>"Post does not exist", "status"=>false]);

$acting_user = $_SESSION['user']['id'];
$subject_user = $post->user->id;

require_once("models/Event.class.php");

$event = Event::get()->where("post=$postid AND acting_user=$acting_user AND action='like'")->limit(1)->send();

if($event){
	$event->delete()->send();
	Utils::finalResponse(["message"=>"Unliked", "status"=>true]);
}else{
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
}

?>