<?php
if(!isset($get['id']) || empty($get['id']))
	die("Lol whut? This isnt how this is supposed to work... you need a filter ID");

require_once("src/database.php");

$filters = select(["what"=>"image", "from"=>"filters", "where"=>"id={$get['id']}"]);
if(empty($filters))
	die("Invalid filter ID");
$filter = $filters[0];
if($im = imagecreatefromstring($filter['image'])){
	header("Content-Type: image/png");
	imagepng($im);
	imagedestroy($im);
}else
	die("Something went wrong creating the image");
exit;


?>