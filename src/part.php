<?php


$payload = json_decode($query->payload, true);
if($dir = Parts::getPart("part::".$payload['id'])){
    header("Content-Type: text/html");
    include($dir);
}

?>