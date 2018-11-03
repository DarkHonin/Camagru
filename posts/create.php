<?php
require_once("models/Sticker.class.php");
require_once("models/Post.class.php");
require_once("models/User.class.php");
if($err = User::verify()){
	include_once("page/logout.php");
	return;
}
$payload = $_POST;

require_once("parts/forms/Comment.form.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new CommentFrom("", "Describe your post", 0);

$error = [];
$builder->valid($frm, $payload, $error);

if(!empty($error))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);
if(!isset($payload['image']) || empty($payload['image']))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);

$image = json_decode($payload['image'], true);

if(!isset($image['userImage']) || empty($image['userImage']))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);

$userImage = $image["userImage"];
$userImage = str_replace("data:image/png;base64,", "", $userImage);

$post = new Post();
$post->image_data = $userImage;
$post->user = $_SESSION['user']['id'];
if(empty($image['stickers'])) $image['stickers'] = [];
$post->overlay = json_encode($image['stickers']);
$post->description = $payload['description'];
$post->insert()->send();
Utils::finalResponse(["message"=>"Your post has been created","data"=>["redirect"=>"/user/".$_SESSION['user']['uname']], "status"=>true]);
?>