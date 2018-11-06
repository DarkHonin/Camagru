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
		Utils::finalResponse(["message"=>"invalid request", "status"=>false]);
	
	$error = [];
	if($payload['role'] === "register")
		$Builder->valid($register, $payload, $error);
	else if($payload['role'] === "login")
		$Builder->valid($login, $payload, $error);
	else
		Utils::finalResponse(["message"=>"invalid request", "status"=>false]);
	if(!empty($error))
		Utils::finalResponse(["message"=>"Some fields were invalid", "data"=>["error"=>$error, "form"=>$payload['role']], "status"=>false]);
	
	if($payload['role'] === "register")
		if($payload['password1'] !== $payload['password2'])
			Utils::finalResponse(["message"=>"Passwords do not match", "status"=>false]);
		else{
			require_once("models/User.class.php");
			$usr = new User();
			$usr->parseArray($payload);
			if($err = $usr->register())
				Utils::finalResponse(["message"=>"Regitration failed", "status"=>false]);
			Utils::finalResponse(["message"=>"Account has been created. Check your email to activate.","redirect" => "/", "status"=>true]);
		}

	if($payload['role'] === "login"){
		require_once("models/User.class.php");
		$usr = new User();
		$usr->uname = $_POST['uname'];
		$usr->password = $_POST['password'];
		$err = $usr->login();
		if($err || !$usr->active)
			Utils::finalResponse(["message"=>"Invalid Username/Password", "status"=>false]);
		$_SESSION["user"] = ["uname"=>$usr->uname, "session_token"=>$usr->session_token, "id"=>$usr->id];
		Utils::finalResponse(["message"=>"You are now logged in, redirecting to user page","redirect" => "/user/{$usr->uname}", "status"=>true]);
	}
	Utils::finalResponse(["message"=>"invalid request", "status"=>false]);
?>