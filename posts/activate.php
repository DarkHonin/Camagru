<?php

require_once("src/classes/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Builder = new FormBuilder();
$Form = new User();

$payload = $_POST;
if(!isset($payload['action']) || empty($payload['action']))
	die(json_encode(["error"=>"invalid request"]));
$Form->setFormType($payload['action']);
if($error = $Builder->validate($Form, $payload))
	header("Location: $part?error=$error");
else{
	if(!isset($_GET['token']) || empty($_GET['token'])){
		echo "Token is not set";
		$user = User::get("email")->where("uname='$Form->uname'")->send();
		$Form->token = sha1(time().$user->uname);
		$Form->update()->where("uname='$Form->uname'")->send();
		send_token_email($user->email, $Form->token);
	}else{
		echo "Token is set\n";
		if($_SESSION['user']['token'] === $_GET['token']){
			echo "Token is valid";
			$Form->active = 1;
			$Form->update()->where("uname='$Form->uname'")->send();
		}else{
			include_once("page/logout.php");
			header("Location: $part?error=Invalid Username/Password");
			exit();
		}
	}
	header("Location: /user/{$payload['uname']}");
}

?>