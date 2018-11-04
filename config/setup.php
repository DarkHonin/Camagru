<?php
$sql = file_get_contents("config/camagru.sql");
self::$_pdo->exec($sql);

if(!file_exists("/prerender"))
    mkdir("/prerender");
?>