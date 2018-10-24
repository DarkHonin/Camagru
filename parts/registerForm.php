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
	
	"token"=>[
		"name" => "scrf",
		"type" => "hidden",
	]
	];
	
function validate(&$fields){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(($ret = validate_post($fields)))
			return $ret;
		if(!check_scrf_token("form_register"))
			return "The page has expired";
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
			mail($_POST['email'], "Verfify Camagru account", "localhost?token='".sha1(time())."'");
			header("Location: /");
		}
	}
	$fields['token']['value'] = create_csrf_token("form_register");

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
<input type='radio' form="loginf" class='toggle' value="register" name='formtoggle' id='reg' <?php echo (!isset($_POST['formtoggle']) ? "checked":"") ?> hidden>
<form method="post" class="toggle">
<?php
foreach($fields as $field){
	echo "<input ";
	foreach($field as $k=>$v)
		echo "$k='$v'";
	echo ">";
}
?>
</form>