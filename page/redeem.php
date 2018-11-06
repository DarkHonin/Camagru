<?php
	require_once("src/classes/form/FormBuilder.class.php");
	require_once("parts/forms/Login.form.php");
	require_once("parts/forms/Register.form.php");
	require_once("models/User.class.php");
	$Builder = new FormBuilder();
	$login = new LoginForm();

	require_once("models/Token.class.php");

	$token = Token::redeem();
	
?>

<div class="anounce <?php
		if(isset($_GET['error']))
			echo "error";
	?> ">
	<?php
		if(isset($_GET['error']))
			echo $_GET['error'];
		else
			echo "Activate/Re-send activation email";
	
	?>
</div>
<div class="col-half-w col-half">
	<div class='col-half-w forms col-hold'>
		<label class="anounce" for="showlogin">Login</label>
		<div>
			<?php if($token && $token->action == "reset_password"){
				?>
				<form method="post">
					<input type="submit" value="Reset password">
				</form>
				<?php
			}else {
				$Builder->renderForm($login, ["class" => "col-full", "id"=>"login"]);
				} ?>
		</div>
	</div>
</div>
<script src="/assets/js/login.js"></script>