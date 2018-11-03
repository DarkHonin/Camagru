<?php

if(!isset($nav[1]) || (!isset($_POST['item']) || empty($_POST['item'])))
    Utils::finalResponse(["status"=>false]);

switch($nav[1]){
    case "uname_available":
        require_once("models/User.class.php");
        $users = User::get("uname")->where("uname='{$_POST['item']}'")->send();
        if(empty($users))
            Utils::finalResponse(["status"=>true]);
        Utils::finalResponse(["status"=>false]);
    case "email_available":
        require_once("models/User.class.php");
        $users = User::get("email")->where("email='{$_POST['item']}'")->send();
        if(empty($users))
            Utils::finalResponse(["status"=>true]);
        Utils::finalResponse(["status"=>false]);
    case "creator_images":
        switch($_POST['item']){
            case "stickers":
                require_once("models/Sticker.class.php");
                $strickers = Sticker::get()->where("type='sticker'")->send();
                if(!$strickers)
                    Utils::finalResponse(["status"=>false]);
                if(!is_array($strickers))
                    $strickers = [$strickers];
                ob_start();
                foreach($strickers as $sticker)
                    include("parts/create/sticker.php");
                $info = ob_get_contents();
                ob_end_clean();
                Utils::finalResponse(["status"=>true, "data"=>$info]);
            case "posts":
                require_once("models/Post.class.php");
                $strickers = Post::get("id")->where("user={$_SESSION['user']['id']}")->send();
                if(!$strickers)
                    Utils::finalResponse(["status"=>false]);
                if(!is_array($strickers))
                    $strickers = [$strickers];
                ob_start();
                foreach($strickers as $post)
                    echo "<img src=/post/{$post->id}/img class='sticker' sticker_id={$post->id} type='post' onclick='addLayer(this)'>";
                $info = ob_get_contents();
                ob_end_clean();
                Utils::finalResponse(["status"=>true, "data"=>$info]);
        }
}


?>