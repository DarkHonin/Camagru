<?php

$user = User::get("uname, id, email")->where("uname='{$_SESSION['user']['uname']}'")->send();

require_once("models/User.class.php");
require_once("parts/forms/settings/UpdateEmail.form.php");
require_once("parts/forms/settings/UpdatePassword.form.php");
require_once("parts/forms/settings/DeleteAccount.form.php");
require_once("parts/forms/settings/UpdateGeneral.form.php");

$Builder = new FormBuilder();
$Email = new UpdateEmail(null);
$Password = new UpdatePassword();
$Delete = new DeleteAccount();
$General = new UpdateGeneral(null, null);

$payload = $_POST;
if(!isset($payload['role']) || empty($payload['role']))
	Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);

switch($payload['role']){
	case "delete_account":
		$user->password = $payload["current"];
		if($user->login)
			Utils::finalResponse(["data"=>["error"=>["global"=>$err], "form"=>$payload['role']], "status"=>false]);
		$token = Token::create($user, $user->uname, "delete_account");
		Utils::send_token_email($user->email, $token->token);
		$token->insert()->send();
		Utils::finalResponse(["message"=>"An instruction email has been send.", "status"=>true]);

	case "update_email":
		$old = Token::get("id")->where("user={$user->id} AND action='verify_email'");
		if(!is_object($old))
			Utils::finalResponse(["message"=>"Outstanding token not redeemed", "status"=>false]);
		$token = Token::create($user, $user->uname, "verify_email", $payload["email"]);
		Utils::send_token_email($payload["email"], $token->token);
		$user->email_valid = 0;
		$user->update()->where("id={$user->id}")->send();
		$token->insert()->send();
		Utils::finalResponse(["message"=>"An activation email has been sent.", "status"=>true]);

	case "update_genneral":
		error_log("Updates: ".$payload["updates"]);
		if(isset($payload["updates"]) || !empty($payload["updates"]))
			$user->recieve_updates = "1";
		else
			$user->recieve_updates = 'false';
		error_log(print_r($user, true));
		if(isset($payload['uname']) && !empty($payload['uname']))
			$user->uname = $payload['uname'];
		$user->update()->where("id={$user->id}")->send();
		$_SESSION['user']['uname'] = $user->uname;
		Utils::finalResponse(["message"=>"Your information has been updated.","data"=>["redirect"=>"/settings"], "status"=>true]);

	case "update_password":
		$user->password = $payload["current"];
		if($err = $user->login())
			Utils::finalResponse(["data"=>["error"=>["global"=>$err], "form"=>$payload['role']], "status"=>false]);
		$user->sha = password_hash($payload["new_password"], PASSWORD_BCRYPT);
		$user->update()->where("id={$user->id}")->send();
		Utils::finalResponse(["message"=>"Your information has been updated.", "status"=>true]);

	default:
		Utils::finalResponse(["data"=>["error"=>["global"=>"invalid request"]], "status"=>false]);
}

?>