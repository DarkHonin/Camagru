<?php
require_once("models/User.class.php");
if(User::verify())
	header("Location: /404");
$user = User::get("uname, id, reg_on, email, recieve_updates")->where("uname='{$_SESSION['user']['uname']}'")->send();

require_once("models/User.class.php");
require_once("parts/forms/settings/UpdateEmail.form.php");
require_once("parts/forms/settings/UpdatePassword.form.php");
require_once("parts/forms/settings/DeleteAccount.form.php");
require_once("parts/forms/settings/UpdateGeneral.form.php");

$Builder = new FormBuilder();
$Email = new UpdateEmail($user->email);
$Password = new UpdatePassword();
$Delete = new DeleteAccount();
$General = new UpdateGeneral($user->uname, $user->recieve_updates);

?>

<div class="anounce">
	<?php echo $user->uname ?>
	<span class="reg_on">Registered on <?php echo $user->reg_on ?></span>
</div>
<div class="col-half-w col-hold forms">
<label class="anounce" for="showgenneral">
		Genneral settings
	</label>
	<input type="radio" id="showgenneral" class="hidden col-toggle" name="action">
	<form method="POST" class="hide">
		<div class="col-3 info">
			Your general user settings
		</div>
		<div>
			<?php $Builder->renderFields($General) ?>
		</div>
	</form>

	<label class="anounce" for="showemail">
		Update email
	</label>
	<input type="radio" id="showemail" class="hidden col-toggle" name="action">
	<form method="POST" class="hide">
		<div class="col-3 info">
			You will need to verify this email address before you can post again
		</div>
		<div>
			<?php $Builder->renderFields($Email) ?>
		</div>
	</form>

	<label href="#showpass" class="anounce" for="showpass">
		Update password
	</label>
	<input type="radio" id="showpass" class="hidden col-toggle" name="action">
	<form method="POST" class="hide">
		<div class="col-3 info">
			Updating your password is recomended every so often to ensure security
		</div>
		<div>
			<?php $Builder->renderFields($Password) ?>
		</div>
	</form>

	<label href="#showpass" class="anounce error" for="showdel">
		Delete Account
	</label>
	<input type="radio" id="showdel" class="hidden col-toggle" name="action">
	<form method="POST" class="hide">
		<div class="col-3 info">
			This is permanent, please be sure you want to do this
		</div>
		<div>
			<?php $Builder->renderFields($Delete) ?>
		</div>
	</form>
</div>

<script src="/assets/js/login.js"></script>