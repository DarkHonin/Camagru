<?php
unset($_SESSION['user']);
session_destroy();
echo json_encode(["reload" => ["menue"]]);
exit();
?>