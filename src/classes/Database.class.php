<?php

class Database{
	public const verbose = false;
	private static $_pdo;

	public static function init(){
		include_once("config/database.php");
		if(self::verbose) echo "Connecting database :: Waiting\n";
		try{
			self::$_pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			  ));
			if(self::verbose) echo "Database Connected :: OK\n";
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
		if(self::verbose) echo "Sending Query : $query\n";
		$class = get_class($query);
		if(self::verbose) echo "Query class : $class\n";
		$str = self::$_pdo->prepare($query);
		try{
			$str->execute();
			if(self::verbose) echo "Query sent\n";
		}catch(PDOException $e){
			$errstr = $class."::error".$e->getCode();
			if(self::verbose) echo "$errstr\n";
			if(is_callable($errstr))
				return $errstr($e);
			throw $e;
		}
		if(($err = $str->errorCode()) != "00000"){
			$errstr = $class."::error$err";
			if(self::verbose) echo "$errstr\n";
			if(is_callable($errstr))
				$errstr();
			return false;
		}
		if(self::verbose) echo "Parsing data\n";

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