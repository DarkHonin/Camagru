<?php
	require_once("src/classes/form/FormBuilder.class.php");
	require_once("parts/forms/Login.form.php");
	require_once("parts/forms/Register.form.php");
	$Builder = new FormBuilder();
	$login = new LoginForm();
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
<div class="anounce">
	Login to continue
</div>
<?php $Builder->renderForm($login, ["class" => "col-full", "id"=>"login"]); ?>
</div>
</div>
<script src="/assets/js/login.js"></script>