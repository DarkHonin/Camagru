<?php
require_once("models/User.class.php");
$user = User::get("id, uname")->where("uname={$payload['uname']}")->send();
if(!$user)
	die("No such user");
?>

<div class="anounce">
	<?php echo $user->uname ?>
</div>