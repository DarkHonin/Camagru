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
<div class="anounce">
	Login to continue
</div>
<?php
require_once("src/classes/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Builder = new FormBuilder();
$user = new User();
$user->setFormType("login");
$Builder->renderForm($user);
?>
</div>