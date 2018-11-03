<?php
require_once("models/Sticker.class.php");
require_once("models/Post.class.php");
require_once("models/User.class.php");
if(!$USER_VALID)
	return;
$payload = $_POST;

require_once("parts/forms/Comment.form.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new CommentFrom("", "Describe your post", 0);

$error = [];
$payload['post'] = -1;
$builder->valid($frm, $payload, $error);

if(!empty($error))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);
if(!isset($payload['image']) || empty($payload['image']))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);

$image = json_decode($payload['image'], true);
error_log("Image object found");
if(!isset($image['userImage']) || empty($image['userImage']))
	Utils::finalResponse(["message"=>"Invalid submission", "data"=>["error"=>$error], "status"=>false]);
error_log("Image data found");
$userImage = $image["userImage"];
$userImage = str_replace("data:image/png;base64,", "", $userImage);
error_log("Image data prepared");
$post = new Post();
$post->image_data = $userImage;
$post->user = $_SESSION['user']['id'];
if(empty($image['stickers'])) $image['stickers'] = [];
$post->overlay = json_encode($image['stickers']);
$post->description = $payload['description'];
$post->insert()->send();
Utils::finalResponse(["message"=>"Your post has been created","data"=>["redirect"=>"/user/".$_SESSION['user']['uname']], "status"=>true]);
?>