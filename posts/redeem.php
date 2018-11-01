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

$payload = $_POST;
if(!isset($payload['role']) || empty($payload['role']))
	Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);
$error = [];
if($payload['role'] === "login")
	$Builder->valid($login, $payload, $error);
else
	Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);
if(!empty($error))
	Utils::finalResponse(["data"=>["error"=>$error, "form"=>$payload['role']], "status"=>false]);
error_log("Information valid");
// Form info is clean and valid... Hopefully //

require_once("models/User.class.php");
$user = new User();
$user->uname = $_POST['uname'];
$user->password = $_POST['password'];
if($err = $user->login())
	Utils::finalResponse(["data"=>["error"=>["global"=>$err], "form"=>$payload['role']], "status"=>false]);

error_log("User login OK");
// Login was OK, user is valid //

if(!$token)
	if($user->active)
		Utils::finalResponse(["data"=>["error"=>["global"=>"The account has no outstanding tokens"]],"status"=>false]);
	else{
		$token = Token::create($user, $user->uname, "activate_account");
		Utils::send_token_email($user->email, $token->token);
		$token->insert()->send();
		Utils::finalResponse(["status"=>true, "message"=>"A new email has been sent"]);
	}
else{
	if($token->user != $user->id)
		Utils::finalResponse(["data"=>["error"=>["global"=>"The account has no outstanding tokens"]],"status"=>false]);
	
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
	}
	if($token)
		$token->delete()->send();
	Utils::finalResponse(["message"=>"The token has been redeemed.", "data"=>["redirect" => "/"],"status"=>true]);

}

?>