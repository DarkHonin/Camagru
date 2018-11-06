<?php

require_once("src/classes/form/FormBuilder.class.php");
require_once("parts/forms/Recover.form.php");
$Builder = new FormBuilder();
$recover = new RecoverForm();

?>
<div class="anounce" id="status">
	Recover your account
</div>
<div class='col-half-w forms col-hold'>
	<label class="anounce" for="showlogin">Login</label>
	<div>
		<?php $Builder->renderForm($recover, ["class" => "col-full", "id"=>"login"]); ?>
	</div>
</div>
