<?php
header("Content-Type: image/png");
ob_end_clean();
if(file_exists("prerender/".$post->id.$post->date.".png"))
	echo file_get_contents("prerender/".$post->id.$post->date.".png");
else
	include_once("src/render.php");
echo file_get_contents("prerender/".$post->id.$post->date.".png");
return;
?>