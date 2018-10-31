<?php
date_default_timezone_set("Africa/Johannesburg");
ini_set ('log_errors', 1);
ini_set ('display_errors', 0);
ini_set ('error_log', __DIR__."/debuglog.log");
session_start();
require_once("src/classes/Utils.class.php");
require_once("src/classes/Parts.class.php");
require_once("models/User.class.php");

$page = $_GET['q'];
if(empty($page))
	$content = "parts/landingcontent.php";
else{
	$nav = explode("/", $page);
	$part = $nav[0];
	if($_SERVER["REQUEST_METHOD"] == "POST")
		return require_once("posts/".$nav[0].".php");
	else
	$content = "page/".$nav[0].".php";
}
if(!file_exists($content))
	$content = "page/404.php";
include_once("page/index.php");
?>