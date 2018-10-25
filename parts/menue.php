<a href="/home" class="nav banner"><img src="assets/img/icon.svg"></a>

<?php
if(!isset($_SESSION['user']) || empty($_SESSION['user']))
	echo "<a class='nav' href='/login'>Register/Login</a>";
else{
	echo "<a class='nav' href='/user'>{$_SESSION['user']['uname']}</a>";
	if(! intval ($_SESSION['user']['active']))
		echo "<span class='nav error'>Not activated</span>";
	else
		echo "<a class='nav' href='/create'>Create</a>";
	echo "<a class='nav' href='/logout'>Logout</a>";
}
?>