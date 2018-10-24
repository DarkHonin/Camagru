<?php

$fields = [
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
		"value"=> "Login"
	]
	];
	
function validate_login(&$fields){
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if(($ret = validate_post($fields)))
			return $ret;
		if(!check_scrf_token("form_login"))
			return "The page has expired";
		return true;
	}
}
if(isset($_POST['formtoggle']) && $_POST['formtoggle'] === "login"){
	$message = validate_login($fields);
	if(is_bool($message) && $message)
		if(!login($_POST['uname'], $_POST['password']))
			$message = "Invalid Username\Password";
		else
			header("Location: /");

}
$fields['token']['value'] = create_csrf_token("form_login");
?>

<label for='login' class="anounce <?php
if(isset($message) && !is_bool($message))
	echo "error" ?>">

<?php
if(isset($message) && !is_bool($message))
	echo $message;
	else
	echo "Login"; ?>
</label>
<input type='radio' name='formtoggle' class='toggle' value="login" id='login' form='loginf' <?php echo  (isset($_POST['formtoggle']) ? "checked":"") ?> hidden>
<form method="post"  id='loginf' class="toggle">
<?php
foreach($fields as $field){
	echo "<input ";
	foreach($field as $k=>$v)
		echo "$k='$v'";
	echo ">";
}
?>
</form>