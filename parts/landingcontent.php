<?php 
header("Content-Type: text/html");
$posts = select(["what"=>"*", "from"=>"posts"]);
if(!isset($posts) || empty($posts)) {?>
	<div class="anounce error">
		There was a problem fetching the posts
	</div>
<?php }else{
	foreach($posts as $post){
		?>
		<div class="post">
			<img src="<?php echo $post['Image'] ?>" >
		<?php
	}
} ?>