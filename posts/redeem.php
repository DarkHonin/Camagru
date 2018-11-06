<?php

require_once("src/classes/form/FormBuilder.class.php");
require_once("parts/forms/Login.form.php");
require_once("parts/forms/Register.form.php");
require_once("models/Token.class.php");
$Builder = new FormBuilder();
$login = new LoginForm();

$token = Token::redeem();
if(!$token)
	error_log("There is no token");

if($token && $token->expired()){
	error_log("token has expired");
	$token->delete()->send();
	$token = null;
}

if(!$token || ($token && $token->action != "reset_password")){
$payload = $_POST;
if(!isset($payload['role']) || empty($payload['role']))
	Utils::finalResponse(["message"=>"invalid request", "status"=>false]);
$error = [];
if($payload['role'] === "login")
	$Builder->valid($login, $payload, $error);
else
	Utils::finalResponse(["message"=>"invalid request", "status"=>false]);
if(!empty($error))
	Utils::finalResponse(["message"=>"Some fields were invalid", "data"=>$error, "status"=>false]);
error_log("Information valid");
// Form info is clean and valid... Hopefully //

require_once("models/User.class.php");
$user = new User();
$user->uname = $_POST['uname'];
$user->password = $_POST['password'];
if(!$user->login())
	Utils::finalResponse(["message"=>"Invalid Username/Password", "data"=>$error, "status"=>false]);

error_log("User login OK");
// Login was OK, user is valid //
}else{
	require_once("models/User.class.php");
	$user = User::get()->where("id={$token->user}")->send();
}
if(!$token)
	if($user->active)
		Utils::finalResponse(["message"=>"The account has no outstanding tokens","status"=>false]);
	else{
		$test = Token::get()->where("user={$user->id}")->send();
		if(!empty($test))
			Utils::finalResponse(["message"=>"The account has outstanding tokens","status"=>false]);
		$token = Token::create($user, $user->uname, "activate_account");
		Utils::send_token_email($user->email, $token->token);
		$token->insert()->send();
		Utils::finalResponse(["status"=>true, "message"=>"A new email has been sent"]);
	}
else{
	if($token->user != $user->id)
		Utils::finalResponse(["message"=>"The account has no outstanding tokens","status"=>false]);
	
	switch($token->action){
		case "activate_account":
			$user->active = 1;
		case "verify_email":
			$user->email_valid = 1;
			$user->email = $token->data;
			$user->update()->where("id=$user->id")->send();
			break;
		case "delete_account":
			$token->delete()->send();
			$token = null;
			$user->delete()->send();
			break;
		case "reset_password":
			$pass = crypt(time(), $usr->uname);
			$user->sha = password_hash($pass, PASSWORD_BCRYPT);
			Utils::sendEmail($usr->email, "Your usename and password is now :  \n\nUsername: $usr->uname\nPassword: $pass", "Password Reset");
			$user->update()->where("id=$usr->id")->send();	
	}
	if($token)
		$token->delete()->send();
	Utils::finalResponse(["message"=>"The token has been redeemed.", "redirect" => "/login","status"=>true]);

}

?>