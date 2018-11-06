<?php

require_once("src/classes/form/FormBuilder.class.php");
require_once("parts/forms/Recover.form.php");

$Builder = new FormBuilder();
$recover = new RecoverForm();

$error = [];
$payload = $_POST;
$Builder->valid($recover, $payload, $error);

if(!empty($error))
	Utils::finalResponse(["message"=>"Some fields were not valid", "form"=>$payload['role'], "status"=>false]);
error_log(print_r($payload, true));
require_once("models/User.class.php");
require_once("models/Token.class.php");
$usr = User::get("id, uname, email")->where("email='{$payload['email']}'")->send();
if(is_object($usr)){
	$test = Token::get()->where("user={$usr->id}")->send();
	if(!empty($test))
		Utils::finalResponse(["message"=>"The account has outstanding tokens","status"=>false]);
	$token = Token::create( $usr, $usr->uname."_passwerd_reset", "reset_password");
	$token->insert()->send();
	Utils::sendEmail($usr->email, "Please follow the link to reset your password: "."http://".$_SERVER['SERVER_NAME'].":8080/redeem?token={$token->token}", "Recovery in effect");
	
	Utils::finalResponse(["message"=>"Your email has been sent", "status"=>true]);
}else
	Utils::finalResponse(["message"=>"The email is not registered","status"=>false]);

?>