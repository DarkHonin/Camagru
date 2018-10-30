<?php
	
	require_once("src/classes/form/FormBuilder.class.php");
	require_once("parts/forms/Login.form.php");
	require_once("parts/forms/Register.form.php");
	$Builder = new FormBuilder();
	$login = new LoginForm();
	$register = new RegisterForm();

	function check_2passmatch($params){
		if($params['password1'] !== $params['password2']){
			return ["password1", "The 2 passwords do not match"];
		}
	}

	$payload = $_POST;
	if(!isset($payload['role']) || empty($payload['role']))
		Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);
	
	$error = [];
	if($payload['role'] === "register")
		$Builder->valid($register, $payload, $error);
	else if($payload['role'] === "login")
		$Builder->valid($login, $payload, $error);
	else
		Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);
	if(!empty($error))
		Utils::finalResponse(["data"=>["error"=>$error, "form"=>$payload['role']], "status"=>false]);
	
	if($payload['role'] === "register")
		if($payload['password1'] !== $payload['password2'])
			Utils::finalResponse(["data"=>["error"=>["password1"=>"Passwords do not match"], "form"=>$payload['role']], "status"=>false]);
		else{
			require_once("src/classes/User.class.php");
			$usr = new User();
			$usr->parseArray($payload);
			if($err = $usr->register())
				Utils::finalResponse(["data"=>["error"=>["global"=>$err], "form"=>$payload['role']], "status"=>false]);
		}

	if($payload['role'] === "login"){
		require_once("src/classes/User.class.php");
		$usr = new User();
		$usr->uname = $_POST['uname'];
		$usr->password = $_POST['password'];
		if($err = $usr->login())
			Utils::finalResponse(["data"=>["error"=>["global"=>$err], "form"=>$payload['role']], "status"=>false]);
	}


	Utils::finalResponse(["status"=>true]);
?>