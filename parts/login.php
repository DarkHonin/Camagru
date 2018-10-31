<?php

require_once("models/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Form = new User();
$Builder = new FormBuilder();

function check_2passmatch($params){
	if($params['password1'] !== $params['password2']){
		return ["password1", "The 2 passwords do not match"];
	}
}
	header("Content-Type: text/html");
?>
<div class="anounce col-full" id="status">
	Welcome
</div>
<div class='col-full col-r forms'>
	<div class="col-half">
		<?php $Builder->renderForm($Form, ["class" => "col-full", "id" => "register"]); ?>
	</div>
	<div class="col-half">
		<?php $Form->setFormType("login");  $Builder->renderForm($Form, ["class" => "col-full", "id" => "login"]); ?>
	</div>
</div>
<script type="module">
	import("./login.js");
</script>