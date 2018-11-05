<?php

require_once("models/User.class.php");

if(!isset($_POST['query']))
    $query = "";
else
    $query = htmlentities($_POST['query']);

if(strpos($query, " ")){
    echo "<div class='anounce error'>You cheeky little ****</div>";
    die();
}

$users = User::get('uname')->where("uname LIKE '$query%'")->send();
if(is_object($users))
    $users = [$users];
if(!$users){
?>
<div class='anounce error'>No users found</div>
<?php
}else{
    foreach($users as $user){
    ?>
    <div class='anounce'><a href="/user/<?php echo $user->uname ?>"><?php echo $user->uname ?></a></div>
<?php
    }
}
?>