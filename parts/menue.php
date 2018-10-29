<a href="/" target='/' class="nav banner"><img class="hide-s icon" src="assets/img/icon.svg"></a>
<a href="/" target='/' class="nav show-s">Home</a>
<?php
if(!isset($_SESSION['user']) || empty($_SESSION['user']))
	echo "<a class='nav' href='/login' target='/login'>Register/Login</a>";
else{
	echo "<a class='nav' href='/user' target='/user'>{$_SESSION['user']['uname']}</a>";
	if( !isset($_SESSION['user']['active']) || !intval ($_SESSION['user']['active']))
		echo "<span class='nav error'>Not activated</span>";
	else
		echo "<a class='nav' href='/create' target='/create'>Create</a>";
	echo "<a class='nav' href='/logout' target='/logout'>Logout</a>";
}
?>