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
			$pdo->
			error_log("Database created");
		}else
			throw($e);
	}
	return $pdo;
}

function select($data){
	global $pdo;
	$query = "SELECT {$data['what']} FROM {$data['from']}".(isset($data['where'])?" WHERE ".$data['where']:"");
	$str = $pdo->prepare($query);
	$str->execute();
	return $str->fetchAll();
}

function update($data){
	global $pdo;
	$query = "UPDATE {$data['table']} SET {$data['set']} WHERE ".$data['where'];
	$str = $pdo->prepare($query);
	$str->execute();
}

function insert_into_db($data){
	global $pdo;
	$query = "INSERT INTO {$data['tabel']} (".implode(", ", array_keys($data['fields'])).") VALUES ('".implode("', '",$data['fields'])."')";
	$str = $pdo->prepare($query);
	$str->execute();
	$affected_rows = $str->errorCode();
	if($affected_rows == "23000")
		return "already exists";
}

$pdo = connect_db();

?>