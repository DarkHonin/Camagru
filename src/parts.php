<?php

header("Content-Type: text/html");

$parts = [
	"menue" => "parts/menue.php"
];

if(isset($_POST['id']) && !empty($_POST['id']) && isset($parts[$_POST['id']]))
	include_once($parts[$_POST['id']]);
else
	die($_POST['id']." is not a valid element");
?>