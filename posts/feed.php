<?php

if(!isset($_POST['user']) || empty($_POST['user']))
    $user = false;
else
    $user = intval($_POST['user']);

if(!isset($_POST['last']) || empty($_POST['last']))
    $last_id = 0;
else
    $last_id = intval($_POST['last']);

if($last_id <= 1)   
    return;

if($USER_VALID && !$user){
    $usrs = $CURRENT_USER->getFollowing();
    $str = ["user=".$CURRENT_USER->id];
    foreach($usrs as $id)
        array_push($str, "user=$id");
    $str = implode(" OR ", $str);
    $str = "($str) AND id<$last_id";
    $posts = Post::get("id, description, user")->where($str)->order('date')->limit(3)->send();
}else{
    $str = ($user? "user=$user":"").($user && $last_id? " AND ":"").($last_id? "id<$last_id":"");
    $posts = Post::get("id, description, user")->where($str)->order('date')->limit(3)->send();
}
if(is_object($posts))
    $posts = [$posts];

foreach($posts as $post)
    include("parts/post.php");
?>

<input type="hidden" id="feedmarker" <?php echo ($user? "user={$user}": "") ?> last_id='<?php echo $last_id - count($posts) ?>'>