<?php
session_start();
require_once("src/database.php");
require_once("src/utils.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Camagru</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css" />
	<device type="media" onchange="update(this.data)"></device>
</head>
