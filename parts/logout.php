<?php
unset($_SESSION['user']);
session_destroy();
echo json_encode(["redirect" => "/home", "reload" => ["menue"]]);
exit();
?>