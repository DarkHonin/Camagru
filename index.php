<?php
	error_reporting(E_ALL ^ E_DEPRECATED);

	session_start();

	if(!isset($_SESSION['obj']))
		$_SESSION['obj'] = [];

	include_once("src/classes/Utils.class.php");
	include_once("src/classes/Parts.class.php");
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!isset($_POST['page']) || empty($_POST['page']))
		return include_once("parts/landing.php");
		$query = json_decode($_POST['page']);
		if(!empty(!$query->payload))
			$payload = json_decode($query->payload);
		if(!empty($query->request_path))
			include_once(Parts::getPart($query->request_path));
		else
			include_once(Parts::getPart($query->current_path));
	}else{
		include_once("parts/landing.php");
	}

	
?>