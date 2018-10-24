<?php

$fields = [
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
		"value"=> "register"
	],
	
	[
		"name" => "scrf",
		"type" => "hidden",
		"value"=> sha1("register".$_SERVER['REMOTE_ADDR']."secret_salt")
	]
	];
	
function validate(&$fields){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if($_POST['scrf'] !== sha1("register".$_SERVER['REMOTE_ADDR']."secret_salt")){
			return "The page has expired";
		}
		foreach($fields as $k=>$f){
			if(isset($f["required"]) && (!isset($_POST[$f['name']]) || empty($_POST[$f['name']])))
				return "Field is required: {$f['placeholder']}";
			if(isset($f["maxlength"]) && strlen($_POST[$f['name']]) > $f["maxlength"])
				return "Field '{$f['placeholder']}' must be shorter than {$f["maxlength"]} characters";
			$fields[$k]['value'] = $_POST[$f['name']];
		}
		if($_POST['password1'] !== $_POST['password2']){
			return "The 2 passwords do not match";
		}
		return true;
	}
}
$message = "";
if(!isset($_POST['formtoggle']))
	$message = validate($fields);
	if(is_bool($message) && $message){
		$status = insert_into_db([
			"tabel"=>"users",
			"fields"=> [
				"uname" => $_POST['uname'],
				"email" => $_POST['email'],
				"sha"	=> password_hash($_POST['password1'], PASSWORD_BCRYPT),
				"token" => sha1(time())
			]
		]);
		if(!is_bool($status))
			$message = $status;
		else{
			mail( $_POST['email'], "Verfify Camagru account", "localhost?token='".sha1(time())."'");
			header("Location: /");
		}
	}
?>

<label for='reg' class="anounce <?php
if(!is_bool($message) && $message)
	echo "error" ?>">

<?php
if(!is_bool($message) && $message)
	echo $message;
	else
	echo "Register"; ?>
</label>
<input type='radio' form="loginf" value="register" name='formtoggle' id='reg' <?php echo (!isset($_POST['formtoggle']) ? "checked":"") ?> hidden>
<form method="post" >
<?php
foreach($fields as $field){
	echo "<input ";
	foreach($field as $k=>$v)
		echo "$k='$v'";
	echo ">";
}
?>
</form>