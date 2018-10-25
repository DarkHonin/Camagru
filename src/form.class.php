<?php

class Form{
	private $_fields;
	public $method;
	private $_id;

	function __construct($id, $method, $fields){
		$this->method = $method;
		$this->_fields = $fields;
		$this->_id = $id;
	}

	function renderFields(){
		if(isset($this->_fields['token']))
			$this->_fields['token']['value'] = create_csrf_token($this->_id);
		foreach($this->_fields as $field){
			echo "<input ";
			foreach($field as $k=>$v)
				echo "$k='$v'";
			echo ">";
		}
	}

	function renderForm($tags = []){
		echo "<form method='$this->method'";
		foreach($tags as $k=>$v)
				echo "$k='$v'";
		echo ">";
		$this->renderFields();
		echo "</form>";
	}

	function validate($additional = null){
		if(($ret = validate_post($this->_fields)))
			return $ret;
		if(!check_scrf_token($this->_id))
			return ["error"=>"The page has expired"];
		if($additional && is_callable($additional))
			return $additional();
	}
}

?>