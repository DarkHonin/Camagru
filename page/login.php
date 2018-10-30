<?php

require_once("src/classes/form/FormBuilder.class.php");
require_once("parts/forms/Login.form.php");
require_once("parts/forms/Register.form.php");
$Builder = new FormBuilder();
$login = new LoginForm();
$register = new RegisterForm();

?>
<div class="anounce" id="status">
	Welcome
</div>
<div class='col-half-w forms col-hold'>
	<label class="anounce" for="showregister">Register</label>
	<input type="radio" id="showregister" class="hidden col-toggle" name="action" checked>
	<div class="hide">
		<?php $Builder->renderForm($register, ["class" => "col-full", "id"=>"register"]); ?>
	</div>
	<label class="anounce" for="showlogin">Login</label>
	<input type="radio" class="col-toggle hidden" name="action" id="showlogin">
	<div class="hide">
		<?php $Builder->renderForm($login, ["class" => "col-full", "id"=>"login"]); ?>
	</div>
	<a class="anounce" href="/recover">
		Forgot your password?
	</a>
</div>

<script src="/assets/js/login.js"></script>