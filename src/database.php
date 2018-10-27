<?php

class Database{
	public const verbose = true;
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

	function sendQuery($query){
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

function select($data, $on_error=null, $parse_data=null){
	global $pdo;
	$query = "SELECT {$data['what']} FROM {$data['from']}".(isset($data['where'])?" WHERE ".$data['where']:"");
	$str = $pdo->prepare($query);
	try{
		$str->execute();
	}catch(PDOException $e){
		$code = $e->errorCode();
		if(!is_callable($on_error))
			$on_error($code);
		else
			throw $e;
		return 0;
	}
	$err = intval($str->errorCode());
	if($err && is_callable($on_error))
		$on_error($err);
	if($parse_data)
		return $parse_data($str);
	else return $str->fetchAll();
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
	$sel = [];
	foreach($data['fields'] as $n=>$f)
		array_push($sel, "$n='$f'");
	$sel = implode(" AND ", $sel);
	return select(["what" => "id", "from"=>$data['tabel'], "where"=>$sel])[0]['id'];
}

Database::init();

?>