<?php
	require_once("models/User.class.php");
	$user = User::get("uname, id, reg_on")->where("uname='$nav[1]'")->send();
	require_once("models/Post.class.php");
	if(!$user){
		include_once("parts/unknown_user.php");
		exit();
	}
?>

<div class="anounce">
	<?php echo $user->uname ?>
	<span class="reg_on">Registered on <?php echo $user->reg_on ?></span>
	<?php 
		if(!User::verify() && $_SESSION['user']['uname'] === $user->uname)
			echo "<a href='/settings'>Settings</a>"
	
	?>
</div>
<div class="col-half">
<?php
	$posts = Post::get()->where("user=$user->id")->send();
	if(!empty($posts)){
		if(!is_array($posts))
			$posts = [$posts];
		foreach($posts as $post)
			echo "<br>$post->Title";
	}else{
		echo "<div class='anounce error'>
		There was a problem fetching the posts
		</div>";
	}
?>
</div>