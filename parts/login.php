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
	
		
if(empty($query->payload)){
	header("Content-Type: text/html");
?>
<div class="anounce" id="status">
	Welcome
</div>
<div class='control'>
	<div class="group">
		<?php $Builder->renderForm($Form, ["class" => "body", "id" => "register"]); ?>
	</div>
	<div class="group">
		<?php $Form->setFormType("login");  $Builder->renderForm($Form, ["class" => "body", "id" => "login"]); ?>
	</div>
</div>
<script type="module">
	import("./login.js");
</script>
<?php
}else{
	$payload = json_decode($query->payload, true);
	if(!isset($payload['action']) || empty($payload['action']))
		die(json_encode(["error"=>"invalid request"]));
	$Form->setFormType($payload['action']);
	if($error = $Builder->validate($Form, $payload))
		Utils::finalResponse($error);
	else
		Utils::finalResponse(["redirect" => "/", "reload"=>["menue"]]);
	/*
	if($payload['action'] == "register"){
		if($errors = $Form->validate($payload, "check_2passmatch"))
			die(json_encode($errors));
		$data = ['tabel' => "users", "fields" => [
			"uname" => $payload['uname'],
			"email" => $payload['email'],
			"sha"	=> password_hash($payload['password1'], PASSWORD_BCRYPT),
			"token" => sha1(time().$payload['uname'])
		]];
		if($err = insert_into_db($data))
			die(json_encode(["error"=>"User $err"]));
		send_token_email($payload['email'], $data['fields']['token']);
		if(login($payload['uname'], $payload['password1']))
			die(json_encode(["redirect" => "/", "reload"=>["menue"]]));
	}else if($payload['action'] == "login"){
		if($errors = $Builder->validate($Form, $payload))
			die(json_encode($errors));
		if(login($payload['uname'], $payload['password']))
			die(json_encode(["redirect" => "/", "reload"=>["menue"]]));
	}*/
}
?>