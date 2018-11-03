<link rel="stylesheet" type="text/css" href="/assets/css/post.css">

<?php

require_once("models/Post.class.php");
require_once("models/User.class.php");

if(!$pid = intval($nav[1])){
    include_once("parts/404.php");
    return;
}

if(!$post = Post::get()->where("id=$pid")->send()){
    include_once("parts/404.php");
    return;
}

if(isset($nav[2]) && $nav[2] == "edit"){
    include_once("parts/post.edit.php");
    return;
}else if(isset($nav[2]) && $nav[2] == "img"){
    include_once("parts/post.img.php");
    return;
}

$user_valid = $USER_VALID;

?>

<div class="anounce">
    Posted at <?php echo $post->date?>
</div>
<?php
$comment = true;

include_once("parts/post.php") ?>