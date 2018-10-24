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
		"name" => "password",
		"type" => "password",
		"required" => true,
		"placeholder" => "Password"
	],	
	[
		"name" => "submit",
		"type" => "submit",
		"value"=> "register"
	],
	
	[
		"name" => "scrf",
		"type" => "hidden",
		"value"=> sha1("login".$_SERVER['REMOTE_ADDR']."secret_salt")
	]
	];
	
function validate_login(&$fields){
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
		return true;
	}
}
if(isset($_POST['formtoggle']) && $_POST['formtoggle'] === "login"){
	$message = validate_login($fields);
	select(["what"=>"sha", "from"=>"users", "where" => "uname='{$_POST['uname']}'"]);
}
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
<input type='radio' name='formtoggle' value="login" id='login' form='loginf' <?php echo  (isset($_POST['formtoggle']) ? "checked":"") ?> hidden>
<form method="post"  id='loginf'>
<?php
foreach($fields as $field){
	echo "<input ";
	foreach($field as $k=>$v)
		echo "$k='$v'";
	echo ">";
}
?>
</form>