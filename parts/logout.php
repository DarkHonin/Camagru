<?php
unset($_SESSION['user']);
session_destroy();
Utils::finalResponse(["refresh" => true, "reload" => ["menue"]]);
?>