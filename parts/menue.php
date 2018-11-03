<a href="/" class="nav banner"><img class="hide-s icon" src="/assets/img/icon.svg"></a>
<a href="/" class="nav show-s">Home</a>
<?php
require_once("models/User.class.php");
if(!$USER_VALID)
	echo "<a class='nav' href='/login' >Register/Login</a>";
else{
	echo "<a class='nav' href='/user/{$_SESSION['user']['uname']}'>{$_SESSION['user']['uname']}</a>";
	echo "<a class='nav' href='/create' >Create</a>";
	echo "<a class='nav' href='/logout' >Logout</a>";
}
?>