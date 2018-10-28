<?php 
header("Content-Type: text/html");
require_once("src/classes/Post.class.php");
$posts = Post::get()->send();
if(!isset($posts) || empty($posts)) {?>
	<div class="anounce error">
		There was a problem fetching the posts
	</div>
<?php }else{

	if(!is_array($posts))
		$posts = [$posts];
		foreach($posts as $post){
			?>
			<div class="post">
				<img src="<?php echo $post->Image ?>" >
			<?php
		}
} ?>