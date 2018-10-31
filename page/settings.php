<?php
require_once("models/User.class.php");
if(User::verify())
	header("Location: /404");
$user = User::get("uname, id, reg_on, email")->where("uname='{$_SESSION['user']['uname']}'")->send();

require_once("models/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Builder = new FormBuilder();

?>

<div class="anounce">
	<?php echo $user->uname ?>
	<span class="reg_on">Registered on <?php echo $user->reg_on ?></span>
</div>
<div class="col-half-w col-hold">
	<form method="POST">
		<div class="anounce">
			Update email
		</div>
		<div class="col-3 info">
			Changing your email will result in your account de-avtivating and a new verification being sent.
			You will need to reactivate it before continuing
		</div>
		<div class="col-full">
			<input type="text" name="email" value="<?php echo $user->email ?>">
			<input type="hidden" name="action" value="update_email">
			<input type="hidden" name="token" value="<?php Utils::create_csrf_token("update_email"); ?>">
			<input type="submit" value="Update Email">
		</div>
	</form>

	<form method="POST">
	<div class="anounce">
			Update password
		</div>
		<div class="col-full">
			<input type="password" name="current_password" placeholder="Current password">
			<input type="password" name="new_password" placeholder="New password">
			<input type="hidden" name="action" value="update_password">
			<input type="hidden" name="token" value="<?php Utils::create_csrf_token("update_password"); ?>">
			<input type="submit" value="Update Password">
		</div>
	</form>

	<form method="POST">
	<div class="anounce error">
			Delete account
		</div>
		<div class="col-full">
			<input type="password" name="current_password" placeholder="Current password">
			<input type="hidden" name="action" value="delete">
			<input type="hidden" name="token" value="<?php Utils::create_csrf_token("update_password"); ?>">
			<input type="submit" value="Update Password">
		</div>
	</form>
</div>
