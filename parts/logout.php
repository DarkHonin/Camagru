<?php
unset($_SESSION['user']);
session_destroy();
Utils::finalResponse(["reload" => ["menue"]]);
?>