<a href="/" class="nav banner"><img class="hide-s icon" src="/assets/img/icon.svg"></a>
<a href="/" class="nav show-s">Home</a>
<?php
require_once("src/classes/User.class.php");
if(User::verify())
	echo "<a class='nav' href='/login' >Register/Login</a>";
else{
	echo "<a class='nav' href='/user/{$_SESSION['user']['uname']}'>{$_SESSION['user']['uname']}</a>";
	if( !isset($_SESSION['user']['active']) || !intval ($_SESSION['user']['active']))
		echo "<span class='nav error'>Not activated</span>";
	else
		echo "<a class='nav' href='/create' >Create</a>";
	echo "<a class='nav' href='/logout' >Logout</a>";
}
?>