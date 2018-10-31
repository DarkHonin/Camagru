<?php

require_once("models/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Form = new User();
$Builder = new FormBuilder();
$Form->setFormType = "login";

	if(isset($query->payload) && !empty(($query->payload))){
		$payload = json_decode($query->payload, true);
		if($errors = $Builder->validate($Form, $payload))
			Utils::finalResponse($errors);			
	
		
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