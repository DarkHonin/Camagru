<?php
	include_once("src/includes.php");
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