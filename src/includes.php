<?php
session_start();
require_once("src/database.php");
require_once("src/utils.php");

require_once("src/parts.class.php");
if(isset($_SESSION['user']))
update_user();
?>