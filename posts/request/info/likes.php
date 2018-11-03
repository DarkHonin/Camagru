<?php

require_once("models/Post.class.php");
$post = Post::get("id")->where("id={$payload['data']}")->send();
if(empty($post) || !$post)
	Utils::finalResponse($FALSE_REQUEST);
if(!is_array($post))
	Utils::finalResponse(VALUE_RESPONSE(1, true));
Utils::finalResponse(VALUE_RESPONSE(count($post->getLikes()), true));

?>