
<div class="col-half-w col-hold post">
    <span class="user anounce"><?php echo $post->user->uname ?></span>
    <img src="/post/<?php echo $post->id ?>/img" class="img">
    <div class="desc">
        <?php echo $post->description; ?>
    </div>
    <?php if($user_valid && $_SESSION['user']['id'] == $post->user->id) {?>
        <div class="admin control">
            <a href="edit">Edit this post</a>
            <span class="anounce error">Delete</span>
        </div>
    <?php }?>
    <div class='meta control'>
        <span class="likes" onclick="like_post()">69</span>
        <span class="comments">69</span>
    </div>
    <div class="comments">
        <?php if($user_valid) {?>
            <div class="comment">
                <textarea id="comment_box"></textarea>
                <button onclick="comment()">Post Comment</button>
            </div>
        <?php }?>
        <div class="comment">
            <span class="user">SomeBoob</span>
            <p>Hi I am a comment!</p>
        </div>
    </div>

</div>