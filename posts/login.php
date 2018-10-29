<?php

	require_once("src/classes/User.class.php");
	require_once("src/classes/FormBuilder.class.php");
	$Form = new User();
	$Builder = new FormBuilder();

	function check_2passmatch($params){
		if($params['password1'] !== $params['password2']){
			return ["password1", "The 2 passwords do not match"];
		}
	}

	$payload = $_POST;
	if(!isset($payload['action']) || empty($payload['action']))
		die(json_encode(["error"=>"invalid request"]));
	$Form->setFormType($payload['action']);
	if($error = $Builder->validate($Form, $payload))
		header("Location: $part?error=$error");
	else
		header("Location: /user/{$payload['uname']}");
?>