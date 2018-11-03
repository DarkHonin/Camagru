<link rel="stylesheet" type="text/css" href="/assets/css/post.css">


<?php

	if(!isset($nav[1])){
		include_once("parts/unknown_user.php");
		return;
	}

	require_once("models/User.class.php");
	$user = User::get("uname, id, reg_on")->where("uname='$nav[1]'")->send();
	require_once("models/Post.class.php");
	if(!$user){
		include_once("parts/unknown_user.php");
		return;
	}
?>
<link rel="stylesheet" type="text/css" href="/assets/css/post.css">
<div class="anounce">
	<?php echo $user->uname ?>
	<span class="reg_on">Registered on <?php echo $user->reg_on ?></span>
	<?php 
		if($USER_VALID && $CURRENT_USER->id === $user->id)
			echo "<a href='/settings'>Settings</a>";
		if($USER_VALID && $CURRENT_USER->id !== $user->id){
			if($user->doesFollow($CURRENT_USER))
				echo "<a href='#' onclick='follow_user(this)' user_id='{$user->id}' >Unfollow</a>";
			else
				echo "<a href='#' onclick='follow_user(this)' user_id='{$user->id}' >Follow</a>";
		}
	?>
</div>
<div class="col-half">
<?php
	$posts = Post::get("id, user, date")->where("user=$user->id")->order("date", "DESC")->send();
	if($posts){
		if(!is_array($posts))
			$posts = [$posts];
		foreach($posts as $post)
			include("parts/post.php");
	}else{
		echo "<div class='anounce error'>
		There was a problem fetching the posts
		</div>";
	}
?>
</div>