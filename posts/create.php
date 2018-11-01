<?php
require_once("models/Sticker.class.php");
require_once("models/Post.class.php");
require_once("models/User.class.php");
if($err = User::verify()){
	include_once("page/logout.php");
	return;
}
$payload = json_decode($_POST['image'], true);

$userImage = $payload["userImage"];
$userImage = str_replace("data:image/png;base64,", "", $userImage);
$userImage = base64_decode($userImage);
$userImage = imagecreatefromstring($userImage);

imagesavealpha($userImage, true);
$final = $userImage;
error_log("image data decoded");
foreach($payload['stickers'] as $stk){
	$sticker = Sticker::get()->where("id={$stk['id']}")->send();
	$img = imagecreatefromstring($sticker->filter_image);
	imagealphablending($img, false);
    imagesavealpha($img, true);
	error_log("sticker created");
	$img = imagescale($img, $stk['width']);
	error_log("sticker scaled : ".$stk['width']);
	$img = imagerotate ($img, -$stk['rotate'], imageColorAllocateAlpha($img, 0, 0, 0, 127));
	error_log("sticker rotated");
	$x = $stk['offset']['x'] - (imagesx($img) / 2);
	$y = $stk['offset']['y'] - (imagesy($img) / 2);
	error_log("x: $x y: $y");
	imagecopy($final, $img, $x, $y, 0, 0, imagesx($img), imagesy($img));
	error_log("sticker rendered");
}

$post = new Post();
ob_start();
imagepng($final);
$post->image_data = base64_encode(ob_get_contents());
ob_end_clean();
$post->user = $_SESSION['user']['id'];
$post->insert()->send();
$poastID = (Post::get("id")->where("user={$_SESSION['user']['id']}")->order("id")->limit(1)->send())->id;
Utils::finalResponse(["message"=>"Your post has been created","data"=>["redirect"=>"/post/$poastID/edit"], "status"=>true]);
?>