<?php
header("Content-Type: image/png");
ob_clean();

include_once("models/Sticker.class.php");
$userImage = base64_decode($post->image_data);
$userImage = imagecreatefromstring($userImage);

$overlay = json_decode($post->overlay, true);

imagesavealpha($userImage, true);
$final = $userImage;
error_log("image data decoded");
foreach($overlay as $stk){
	if($stk['type'] == "sticker"){
		$sticker = Sticker::get()->where("id={$stk['id']}")->send();
		$img = imagecreatefromstring(base64_decode($sticker->image_data));
	}
	if($stk['type'] == "post")
		$img = imagecreatefrompng("http://".$_SERVER['SERVER_NAME']."/post/{$stk['id']}/img");
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

imagepng($final);
return;
?>