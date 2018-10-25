<?php
	include_once("src/includes.php");
	header("Content-Type: application/json");

	$uri = $_SERVER["REQUEST_URI"];
	if($_SERVER["REQUEST_METHOD"] == "GET"){
		$str = parse_url($uri);
		$uri = $str['path'];
		$get = [];
		if(isset($str['query']))
		foreach(explode("&", $str['query']) as $q){
			$c = explode("=", $q);
			$get[$c[0]] = $c[1];
		}
	}
	switch($uri){
		case "/":
			include_once("parts/landing.php");
			break;
		case "/login":
			include_once("parts/login.php");
			break;
		case "/logout":
			include_once("parts/logout.php");
			break;
		case "/create":
			include_once("parts/create.php");
			break;
		case "/home":
			include_once("parts/landingcontent.php");
			break;
		case "/part":
			include_once("src/parts.php");
			break;
		case "/filter":
			include_once("src/filter.php");
			break;
		default:
			var_dump($str);
			die("Unknown page: ");
	}
?>