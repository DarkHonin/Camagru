<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	include_once("src/includes.php");
	require_once("src/User.class.php");
	require_once("src/Post.class.php");
	require_once("src/FormBuilder.class.php");
	
	$user = new User();
	$user->uname = "Damnit";
	$user->insert()->send();
	
	die();

	header("Content-Type: application/json");

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!isset($_POST['page']) || empty($_POST['page']))
			include_once("parts/landing.php");
		$query = json_decode($_POST['page']);
		if(!empty($query->request_path))
			include_once(Parts::getPart($query->request_path));
		else
			include_once(Parts::getPart($query->current_path));
	}else{
		include_once("parts/landing.php");
	}
?>