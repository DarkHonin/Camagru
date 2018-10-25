

<?php
function check_2passmatch(){
	if($_POST['password1'] !== $_POST['password2']){
		return ["password1", "The 2 passwords do not match"];
	}
}
	$register = new Form("register", "POST", [
		[
			"name" => "uname",
			"type" => "text",
			"maxlength" => "25",
			"required" => true,
			"placeholder" => "Username",
			"pattern" => "^[A-Za-z0-9_]{1,15}$"
		],
		[
			"name" => "email",
			"type" => "email",
			"maxlength" => "36",
			"required" => true,
			"placeholder" => "Email"
		],
		[
			"name" => "password1",
			"type" => "password",
			"required" => true,
			"placeholder" => "Password"
		],
		[
			"name" => "password2",
			"type" => "password",
			"required" => true,
			"placeholder" => "Re-enter Password"
		],
		[
			"name" => "submit",
			"type" => "submit",
			"value"=> "register",
			"class"=>"anounce"
		],
		"token"=>[
			"name" => "csrf",
			"type" => "hidden"
			
		],[
			"name" => "action",
			"type" => "hidden",
			"value"=> "register"
		]
		]);

		$login = new Form("login", "POST", [
		"token"=>[
			"name" => "csrf",
			"type" => "hidden"
		],
		[
			"name" => "action",
			"type" => "hidden",
			"value"=> "login"
		],
		[
			"name" => "uname",
			"type" => "text",
			"maxlength" => "25",
			"required" => true,
			"placeholder" => "Username",
			"pattern" => "^[A-Za-z0-9_]{1,15}$"
		],
		[
			"name" => "password",
			"type" => "password",
			"required" => true,
			"placeholder" => "Password"
		],	
		[
			"name" => "submit",
			"type" => "submit",
			"value"=> "Login",
			"class"=>"anounce"
		]
		]);

		?>
<?php
if(empty($query->payload)){
	header("Content-Type: text/html");
?>
<div class="anounce" id="status">
	Welcome
</div>
<div class='control'>
	<div class="group">
		<?php $login->renderForm(["class" => "body", "id" => "login"]); ?>
	</div>
	<div class="group">
		<?php $register->renderForm(["class" => "body", "id" => "register"]); ?>
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

	if($payload['action'] == "register"){
		if($errors = $register->validate($payload, "check_2passmatch"))
			die(json_encode($errors));
		$data = ['tabel' => "users", "fields" => [
			"uname" => $payload['uname'],
			"email" => $payload['email'],
			"sha"	=> password_hash($payload['password1'], PASSWORD_BCRYPT),
			"token" => sha1(time().$payload['uname'])
		]];
		if($err = insert_into_db($data))
			die(json_encode(["error"=>"User $err"]));
		if(login($payload['uname'], $payload['password1']))
			die(json_encode(["redirect" => "/", "reload"=>["menue"]]));
	}else if($payload['action'] == "login"){
		if($errors = $login->validate($payload))
			die(json_encode($errors));
		if(login($payload['uname'], $payload['password']))
			die(json_encode(["redirect" => "/", "reload"=>["menue"]]));
	}
}
?>