

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
			"name" => "scrf",
			"type" => "hidden"
			
		]
		]);

		$login = new Form("login", "POST", [
		"token"=>[
			"name" => "scrf",
			"type" => "hidden"
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
if($_SERVER["REQUEST_METHOD"] == "GET"){
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
<script>
	content.querySelectorAll("form").forEach((i) => {i.addEventListener("submit", submit_form);})
	var status = content.querySelector("#status");

	function submit_form(event){
		event.preventDefault();
		let method = event.target.method;
		let fd = new FormData(event.target);
		fd.set("action", event.target.id);
		console.log("sending data to server");
		ajax(method, "/login", fd, handle_form_response);
	}

	function handle_form_response(data){
		var item = JSON.parse(data);
		if(item.error){
			status.innerHTML = item.error;
			status.classList.add("error");
		}	
	}

	document.partload = function(){
		
	};

	document.partunload  = function (){
		
	}
</script>
<?php
}else if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(!isset($_POST['action']) || empty($_POST['action']))
		die(json_encode(["error"=>"invalid request"]));

	if($_POST['action'] == "register"){
		if($errors = $register->validate("check_2passmatch"))
			die(json_encode($errors));
		$data = ['tabel' => "users", "fields" => [
			"uname" => $_POST['uname'],
			"email" => $_POST['email'],
			"sha"	=> password_hash($_POST['password1'], PASSWORD_BCRYPT),
			"token" => sha1(time().$_POST['uname'])
		]];
		if($err = insert_into_db($data))
			die(json_encode(["error"=>"User $err"]));
		if(login($_POST['uname'], $_POST['password1']))
			die(json_encode(["redirect" => "/home", "reload"=>["menue"]]));
	}else if($_POST['action'] == "login"){
		if($errors = $login->validate())
			die(json_encode($errors));
		if(login($_POST['uname'], $_POST['password']))
			die(json_encode(["redirect" => "/home", "reload"=>["menue"]]));
	}
}
?>