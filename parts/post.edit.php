<?php

require_once("models/User.class.php");
if($err = User::verify()){
	include_once("page/logout.php");
	return;
}

require_once("parts/forms/EditPost.form.php");
require_once("src/classes/form/FormBuilder.class.php");

$builder = new FormBuilder();
$frm = new EditPostForm($post->description);

?>

<div class="anounce" id="status">
	Edit your post!
</div>
<div class='col-half-w forms col-hold'>
    <img src=<?php echo "/post/{$post->id}/img"?>>
	<div class="">
		<?php $builder->renderForm($frm, ["class" => "col-full", "id"=>"update"]); ?>
	</div>
</div>

<script src="/assets/js/login.js"></script>
