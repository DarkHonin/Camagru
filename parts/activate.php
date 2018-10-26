<?php
	$login = new Form("activate", "POST", [
		"token"=>[
			"name" => "csrf",
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
	if(isset($query->payload) && !empty(($query->payload))){
		$payload = json_decode($query->payload, true);
		if($errors = $login->validate($payload))
			die(json_encode($errors));
			
		login($payload['uname'], $payload['password']);
			
	
		if(!check_csrf_token($_SESSION['user']['token'].$_SESSION['user']['uname'], $_SESSION['act']))
			include_once("logout.php");
		update(["table"=>"users", "set"=>"active=1", "where" => "uname='{$_SESSION['user']['uname']}'"]);
		update_user();
		die(json_encode(["redirect" => "/", "reload"=>["menue"]]));
	}else{
		if(!isset($query->get_query))
			die(json_encode(["redirect" => "/"]));
		$query->get_query = str_replace("?", "", $query->get_query);
		$params = [];
		foreach(explode("&",$query->get_query) as $p){
			$q = explode("=",$p);
			$params[$q[0]] = $q[1];
		}
		if(!isset($params['token']))
			die(json_encode(["redirect" => "/"]));
		$users = select(["what"=>"uname, token", "from"=>"users", "where" => "token='{$params['token']}'"]);
		if(empty($users))
			die(json_encode(["redirect" => "/"]));
		$user = $users[0];
		$_SESSION['act'] = create_csrf_token($params['token'].$user['uname']);

		header("Content-Type: text/html");
		?>
		<div class="anounce" id="status">
			Activate your account
		</div>
		<div class='control'>
		<div class="group">
		<?php
		$login->renderForm(["class" => "body"]);?>
			</div>
		</div>
		<?php
	}
?>