<?php

require_once("src/classes/form/FormBuilder.class.php");
require_once("parts/forms/Recover.form.php");
$Builder = new FormBuilder();
$recover = new RecoverForm();

$error = [];
$payload = $_POST;
$Builder->valid($recover, $payload, $error);

if(!empty($error))
	Utils::finalResponse(["data"=>["error"=>$error, "form"=>$payload['role']], "status"=>false]);
require_once("models/User.class.php");
$usr = User::get("id, uname, email")->where("email='{$payload['email']}'")->send();

if($usr && !is_array($usr)){
	$pass = crypt(time(), $usr->uname);
	$usr->sha = password_hash($pass, PASSWORD_BCRYPT);
	Utils::sendEmail($usr->email, "Your usename and password is now :  \n\nUsername: $usr->uname\nPassword: $pass", "Password Reset");
	$usr->update()->where("id=$usr->id")->send();
}
Utils::finalResponse(["status"=>true]);

?>