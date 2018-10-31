<?php

require_once("src/classes/form/FormBuilder.class.php");
require_once("parts/forms/Login.form.php");
require_once("parts/forms/Register.form.php");
$Builder = new FormBuilder();
$login = new LoginForm();

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
$usr = new User();
$usr->uname = $_POST['uname'];
$usr->password = $_POST['password'];
if($err = $usr->login())
	Utils::finalResponse(["data"=>["error"=>["global"=>$err], "form"=>$payload['role']], "status"=>false]);

error_log("User login OK");
// Login was OK, user is valid //

require_once("models/Token.class.php");

if(!isset($_GET['token']) || empty($_GET['token']))
	$token = null;
else{
	$token = Input::USER_TOKEN($_GET['token']);
	if(!$token->valid())
		$token = null;
	else
		$stored_token = Token::get()->where("user=$usr->id AND token='$token->value'")->send();
}

				// SANITATION LAYER ENDS //

$TOKEN_TIMEOUT = 60*60*2;

if($token){
	error_log("Token is set and valid");
	// User is attempting to activate thier account //
	if($stored_token->expired()){
		$stored_token->delete()->send();
		Utils::finalResponse(["data"=>["error"=>["global"=>"Your token has expired, please try again"]],"status"=>true]);
	}
	error_log("Token Action: $stored_token->action");
	switch($stored_token->action){
		case "activate_account":
			$usr->active = 1;
		case "verify_email":
			$usr->email_valid = 1;
			$usr->update()->where("id=$usr->id")->send();
			break;
		case "delete_account":
			$usr->delete()->send();
			break;			
	}
	$stored_token->delete()->send();
	Utils::finalResponse(["status"=>true]);
}else{
	error_log("No valid token");
	// User is requesting a resend of activation email //
	if(!$usr->active){
		error_log("Requesting activation email");
		$check = Token::get()->where("user=$usr->id AND action='activate_account'")->send();
		if(!$check){
			// No token in system //

			$token = Token::create($usr, $usr->uname, "activate_account");
			Utils::send_token_email($usr->email, $token->token);
			$token->insert()->send();
			Utils::finalResponse(["status"=>true, "info"=>"A new email has been sent"]);
		}

		// Token exists in system //
		if($check->expired()){
			error_log("token has expired");
			$check->delete()->send();
			$token = Token::create($usr, $usr->uname, "activate_account");
			Utils::send_token_email($usr->email, $token->token);
			$token->insert()->send();
			Utils::finalResponse(["status"=>true, "info"=>"A new email has been sent"]);
		}
		// There are waiting tokens //
		Utils::finalResponse(["data"=>["error"=>["global"=>"The account has outstanding tokens"]],"status"=>true]);
	}
	
}
Utils::finalResponse(["data"=>["error"=>["global"=>"The account has no outstanding tokens"]],"status"=>false]);

?>