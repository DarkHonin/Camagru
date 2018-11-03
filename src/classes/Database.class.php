<?php

class Database{
	public const verbose = false;
	private static $_pdo;

	public static function init(){
		include_once("config/database.php");
		error_log( "Connecting database :: Waiting");
		try{
			self::$_pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			  ));
			error_log( "Database Connected :: OK");
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
	}

	static function sendQuery($query){
		error_log( "Sending Query : $query");
		$class = get_class($query);
		error_log( "Query class : $class");
		$str = self::$_pdo->prepare($query);
		try{
			$str->execute();
			error_log( "Query sent");
		}catch(PDOException $e){
			$errstr = $class."::error".$e->getCode();
			error_log( "$errstr");
			if(is_callable($errstr))
				return $errstr($e);
			throw $e;
		}
		if(($err = $str->errorCode()) != "00000"){
			$errstr = $class."::error$err";
			error_log( "$errstr");
			if(is_callable($errstr))
				$errstr();
			return false;
		}
		error_log( "Parsing data");
		if($str->rowCount() > 1)
			return $str->fetchAll(PDO::FETCH_CLASS, $class);
		try{
			return $str->fetchObject($class);
		}catch(PDOException $e){
			try{
				return $str->fetch();
			}catch(PDOException $e){
				return 1;
			}
		}
	}
}

Database::init();

?>