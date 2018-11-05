<?php
$sql = file_get_contents("config/camagru.sql");
self::$_pdo->exec($sql);
$sql = file_get_contents("config/data.sql");
$parts = explode(";", $sql);
//self::refresh($DB_DSN, $DB_USER, $DB_PASSWORD);
foreach($parts as $p){
    self::$_pdo->exec($p);
    //self::refresh($DB_DSN, $DB_USER, $DB_PASSWORD);
}

?>