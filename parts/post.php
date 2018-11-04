<?php 
require_once("models/Comment.class.php");

if(!isset($comment))
    $comments = Comment::get()->where("post={$post->id}")->order("date", "DESC")->limit(5)->send();
else
    $comments = Comment::get()->where("post={$post->id}")->order("date", "DESC")->send();
if( $comments && !is_array($comments) && !empty($comments))
    $comments = [$comments];
?>
<div class="col-half-w col-hold post" id="post_<?php echo $post->id ?>">
    <div class="anounce">
    <a href="/post/<?php echo $post->id ?>" class="date"><?php echo $post->date ?></a>
    <a href="/user/<?php echo $post->user->uname ?>" class="user"><?php echo $post->user->uname ?></a>
    </div>
    <img src="/post/<?php echo $post->id ?>/img" class="img">
    <div class="desc">
        <?php echo $post->description; ?>
    </div>
    <?php if($USER_VALID && $_SESSION['user']['id'] == $post->user->id) {?>
        <div class="admin control">
            <a href="/post/<?php echo $post->id ?>/edit">Edit this post</a>
            <a href="#" post_id="<?php echo $post->id ?>" onclick="delete_post(this)" class="anounce error">Delete</a>
        </div>
    <?php }?>
    <div class='meta control'>
        <span class="likes" post_id="<?php echo $post->id ?>" onclick="like_post(this)"><?php echo $post->getLikes(); ?></span>
        <span class="comments"><?php echo $post->getCommentCount(); ?></span>
    </div>
    <div class="comments">
        <?php if($USER_VALID && isset($comment)) {
            require_once("parts/forms/Comment.form.php");
            require_once("src/classes/form/FormBuilder.class.php");
            
            $builder = new FormBuilder();
            $frm = new CommentFrom("", "Write a comment", $post->id);
            $builder->renderForm($frm, ["action"=>"/comment"]);
        }
        if($comments)
        foreach($comments as $com){

            ?>
        <div class="comment">
            <span class="user"><?php echo $com->user->uname ?></span>
            <p><?php echo $com->comment ?></p>
        </div>
        <?php } ?>
    </div>

</div>