<?php
require_once("src/classes/User.class.php");
if(User::verify())
	header("Location: /404");
$user = User::get("uname, id, reg_on, email")->where("uname='{$_SESSION['user']['uname']}'")->send();

require_once("src/classes/User.class.php");
require_once("src/classes/FormBuilder.class.php");
$Builder = new FormBuilder();

?>

<div class="anounce">
	<?php echo $user->uname ?>
	<span class="info">Registered on <?php echo $user->reg_on ?></span>
</div>
<div class="col-half-w col-half">
	<form method="POST">
		<div class="anounce">
			Update email
		</div>
		<input type="text" name="email" value="<?php echo $user->email ?>">
		<input type="hidden" name="action" value="update_email">
		<input type="hidden" name="token" value="<?php Utils::create_csrf_token("update_email"); ?>">
		<input type="submit" value="Update Email">
	</form>

	<form method="POST">
	<div class="anounce">
			Update password
		</div>

		<input type="password" name="current_password" placeholder="Current password">
		<input type="password" name="new_password" placeholder="New password">
		<input type="hidden" name="action" value="update_password">
		<input type="hidden" name="token" value="<?php Utils::create_csrf_token("update_password"); ?>">
		<input type="submit" value="Update Password">
	</form>

	<form method="POST">
	<div class="anounce error">
			Delete account
		</div>

		<input type="password" name="current_password" placeholder="Current password">
		<input type="password" name="new_password" placeholder="New password">
		<input type="hidden" name="action" value="delete">
		<input type="hidden" name="token" value="<?php Utils::create_csrf_token("update_password"); ?>">
		<input type="submit" value="Update Password">
	</form>
</div>
