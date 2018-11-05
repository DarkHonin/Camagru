<?php

include_once("models/Sticker.class.php");
$userImage = base64_decode($post->image_data);
$userImage = imagecreatefromstring($userImage);

$overlay = json_decode($post->overlay, true);
if(!file_exists("prerender"))
    mkdir("prerender");
imagesavealpha($userImage, true);
$final = $userImage;
error_log("image data decoded");
foreach($overlay as $stk){
	if($stk['type'] == "sticker"){
		$sticker = Sticker::get()->where("id={$stk['id']}")->send();
		$img = imagecreatefromstring(base64_decode($sticker->filter_image));
	}
	if($stk['type'] == "post"){
        $p = Post::get()->where("id={$stk['id']}")->send();
        $img = imagecreatefrompng("prerender/".$p->id.$p->date.".png");
    }
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

imagepng($final, "prerender/".$post->id.$post->date.".png");

?>