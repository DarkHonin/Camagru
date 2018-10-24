<a href="/" class="banner"><img src="assets/img/icon.svg"></a>

<?php
if(!isset($_SESSION['uname']) || empty($_SESSION['uname']))
	echo "<a class='nav' href='/welcome.php'>Register/Login</a>";
else{
	echo "<a class='nav' href='/user.php'>{$_SESSION['uname']}</a>";
	echo "<a class='nav' href='/create.php'>Create</a>";
	echo "<a class='nav' href='/logout.php'>Logout</a>";
}
?>