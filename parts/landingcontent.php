
<link rel="stylesheet" type="text/css" href="/assets/css/post.css">
<div class="anounce">
	Your feed sir
</div>
<?php 
header("Content-Type: text/html");
require_once("models/Post.class.php");
if($USER_VALID){
	$usrs = $CURRENT_USER->getFollowing();
	$str = ["user={$CURRENT_USER->id}"];
	foreach($usrs as $id)
		array_push($str, "user=$id");
	$str = implode(" OR ", $str);
	$posts = Post::get()->where($str)->order('date')->limit($FEED_POST_COUNT)->send();
}else
	$posts = Post::get()->order("date")->limit($FEED_POST_COUNT)->send();
	if(!isset($posts) || empty($posts)) {?>
	<div class="anounce error">
		There are no posts to show yet
	</div>
<?php }else{

	if(!is_array($posts))
		$posts = [$posts];
		foreach($posts as $post){
			include("parts/post.php");
		}
	}

?>

<input type="hidden" id="feedmarker" last_id='<?php echo $posts[count($posts)-1]->id ?>'>