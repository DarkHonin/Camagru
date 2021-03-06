<?php
error_reporting(E_ALL ^ E_DEPRECATED); 
require_once("Database.class.php");

class Query{

	public const verbose = false;

	private $query;

	// By all means call it staticly
	public function get($what=null){
		if(($class = Utils::getCallingQuery()))
			$ret = new $class($class);
		else
			$ret = $this;
		if(!$what)
			$query = "*";
		else
			$query = $what;
		$ret->query = "SELECT $query FROM $ret->table";
		return $ret;
	}

	// Can not be staticly called... bacause error
	public function where($where){
		if(empty($where))
			return $this;
		$this->query .= " WHERE $where";
		return $this;
	}

	// Can not be staticly called... bacause error
	public function insert(){
		$params = array_filter($this->getValuesForCols());
		$params = Utils::arrayToInsertValues($params);
		$table = $this->_table;
		$string = "INSERT INTO {$table} ({$params['cols']}) VALUES ({$params['vals']})";
		$this->query = $string;
		return $this;
	}

	// Can not be staticly called... bacause error
	function update(){
		$params = array_filter($this->getValuesForCols());
		foreach($params as $k=>$v)
			if($v === "false")
				$params[$k] = 0;
		$table = $this->_table;
		$string = "UPDATE $table SET ".implode(", ", Utils::arrayToQueryConditions($params));
		$this->query = $string;
		return $this; 
	}

	function order($by, $dir="DESC"){
		$this->query .= " ORDER BY $by $dir";
		return $this;
	}

	function limit($amount){
		$this->query .= " LIMIT $amount";
		return $this;
	}

	private function getValuesForCols(){
		foreach($this->_vars as $k=>$v){
			if($k == "_vars" || $k == "_table") continue;
			if(self::verbose) echo "$k($v) = {$this->$k}\n";
			$this->_vars[$k] = $this->$k;
		}
		return $this->_vars;
	}

	function delete(){
		if(!$this->id)
			die("Could not delete row without ID");
		$this->query = "DELETE FROM $this->table WHERE id={$this->id}";
		return $this;
	}

	function parseArray($array){
		foreach($array as $n=>$v){
			if($n == "_vars" || $n == "_table") continue;
			error_log("Query:: $n = $v");
			$this->$n = $v;
		}
	}

	private static function getTable($className){
		$cls = new ReflectionClass($className);
		return $cls->getStaticPropertyValue("Table");
	}

	private static function getExpectedVars($class){
		
	}

	function send(){
		return Database::sendQuery($this);
	}

	protected $_vars;
	protected $_table;

	function __construct($classname = null){
		$class = $classname;
		if(!$class)
			$class = get_class($this);
		$this->_vars = get_class_vars($class);
		$this->_table = $this->_vars['table'];
		unset($this->_vars['table']);
		unset($this->_vars['_table']);
		unset($this->_vars['_vars']);
	}

	function __toString(){
		return $this->query;
	}
}

?>