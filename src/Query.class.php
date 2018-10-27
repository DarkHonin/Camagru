<?php

class Query{

	private $query;

	// By all means call it staticly
	public function get($what=null){
		if(($class = Utils::getCallingQuery()))
			$table = self::getTable($class);
		else
			$class = get_class($this);
		if(!$what)
			$query = "*";
		else
			$query = $what;
		$ret = new $class();
		$ret->query = "SELECT $query FROM $table";
		return $ret;
	}

	// Can not be staticly called... bacause error
	public function where($where){
		$this->query .= " WHERE $where";
		return $this;
	}

	// Can not be staticly called... bacause error
	public function insert(){
		$params = array_filter(get_object_vars($this));
		$params = Utils::arrayToInsertValues($params);
		$table = self::getTable(get_class($this));
		$string = "INSERT INTO {$table} ({$params['cols']}) VALUES ({$params['vals']})";
		$this->query = $string;
		return $this;
	}

	// Can not be staticly called... bacause error
	function update(){
		$params = array_filter(get_object_vars($this));
		$table = self::getTable(get_class($this));
		$string = "UPDATE $table SET ".implode(", ", Utils::arrayToQueryConditions($params));
		$this->query = $string;
		return $this; 
	}

	private static function getTable($className){
		$cls = new ReflectionClass($className);
		return $cls->getStaticPropertyValue("Table");
	}

	

	function send(){
		return Database::sendQuery($this);
	}

	private function __construct(){

	}

	function __toString(){
		return $this->query;
	}
}

?>