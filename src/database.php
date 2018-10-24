<?php

function connect_db(){
	include_once("config/database.php");
	try{
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	}catch(PDOException $e){
		error_log("Failed to connect");
		if($e->getCode() == "1049"){
			error_log("Database does not exist...");
			$pdo = new PDO($DB_DSN_NODB, $DB_USER, $DB_PASSWORD);
			$pdo->exec("CREATE DATABASE IF NOT EXISTS camagru");
			$pdo->exec("USE camagru");
			error_log("Database created");
		}else
			throw($e);
	}
	return $pdo;
}

function select($data){
	global $pdo;
	$query = "SELECT :select FROM :from".(isset($data['where'])?" WHERE :where":"");
	$str = $pdo->prepare($query);
	$str->execute($data);
	return $str->fetchAll();
}

function insert_into_db($data){
	global $pdo;
	$query = "INSERT INTO {$data['tabel']} (".implode(", ", array_keys($data['fields'])).") VALUES ('".implode("', '",$data['fields'])."')";
	$str = $pdo->prepare($query);
	
	$str->execute();
	$affected_rows = $str->errorInfo();
	print_r($affected_rows);	
}

$pdo = connect_db();

?>