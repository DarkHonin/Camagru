<?php
date_default_timezone_set("Africa/Johannesburg");
ini_set ('log_errors', 0);
ini_set ('display_errors', 0);
ini_set ('error_log', __DIR__."/debuglog.log");
session_start();
require_once("src/classes/Utils.class.php");
require_once("src/classes/Parts.class.php");
require_once("models/User.class.php");

$USER_VALID = false;
$FEED_POST_COUNT = 5;

if(isset($_SESSION['user'])){
	$CURRENT_USER = User::get("id, uname, session_token, email")->where("id={$_SESSION['user']['id']} AND session_token='{$_SESSION['user']['session_token']}' AND active=1")->send();
	$USER_VALID = !is_array($CURRENT_USER) && $CURRENT_USER;
	if($USER_VALID)
		$_SESSION['user']['session_token'] = $CURRENT_USER->session_token;
}


ob_start();
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
$data = ob_get_contents();
ob_end_clean();
echo $data;
?>