<?php

require_once("src/classes/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Builder = new FormBuilder();
$Form = new User();

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