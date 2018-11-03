<?php 
require_once("models/Comment.class.php");

if(!isset($comment))
    $comments = Comment::get()->where("post={$post->id}")->order("date", "DESC")->limit(5)->send();
else
    $comments = Comment::get()->where("post={$post->id}")->order("date", "DESC")->send();
if( $comments && !is_array($comments) && !empty($comments))
    $comments = [$comments];
?>
<div class="col-half-w col-hold post">
    <a href="/post/<?php echo $post->id ?>" class="user anounce"><?php echo $post->user->uname ?></a>
    <img src="/post/<?php echo $post->id ?>/img" class="img">
    <div class="desc">
        <?php echo $post->description; ?>
    </div>
    <?php if($user_valid && $_SESSION['user']['id'] == $post->user->id) {?>
        <div class="admin control">
            <a href="/post/<?php echo $post->id ?>/edit">Edit this post</a>
            <span class="anounce error">Delete</span>
        </div>
    <?php }?>
    <div class='meta control'>
        <span class="likes" onclick="like_post()">69</span>
        <span class="comments"><?php echo $post->getCommentCount(); ?></span>
    </div>
    <div class="comments">
        <?php if($user_valid && isset($comment)) {
            require_once("parts/forms/Comment.form.php");
            require_once("src/classes/form/FormBuilder.class.php");
            
            $builder = new FormBuilder();
            $frm = new CommentFrom("", "Write a comment", $post->id);
            $builder->renderForm($frm, ["action"=>"/comment"]);
        }
        
        foreach($comments as $com){

            ?>
        <div class="comment">
            <span class="user"><?php echo $com->user->uname ?></span>
            <p><?php echo $com->comment ?></p>
        </div>
        <?php } ?>
    </div>

</div>