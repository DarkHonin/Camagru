<?php
require_once("models/Sticker.class.php");
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
imagepng($final, "an image.png");
?>