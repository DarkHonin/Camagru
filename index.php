<?php
	include_once("src/includes.php");
	header("Content-Type: application/json");

	$uri = $_SERVER["REQUEST_URI"];

		$str = parse_url($uri);
		$uri = $str['path'];
		$get = [];
		if(isset($str['query']))
			foreach(explode("&", $str['query']) as $q){
				$c = explode("=", $q);
				$get[$c[0]] = $c[1];
			}

	if($uri === "/part"){
		include_once(Parts::getPart($get['id']));
	}else if($uri === "/filter"){
		include_once("src/filter.php");
	}else{
		$part = Parts::getPart("page::$uri");
		include_once("parts\\landing.php");
	}
?>