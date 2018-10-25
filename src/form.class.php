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

	function validate($input, $additional = null){
		foreach($this->_fields as $k=>$f){
			if(isset($f["required"]) && (!isset($input[$f['name']]) || empty($input[$f['name']])))
				return [$f['name'] => "Field is required"];
			if(isset($f["maxlength"]) && strlen($input[$f['name']]) > $f["maxlength"])
				return [$f['name'] => "Maximum amount of characters: {$f["maxlength"]}" ];
			if(isset($input[$f['name']]))
				$this->_fields[$k]['value'] = $input[$f['name']];
		}
		if(!check_csrf_token($this->_id, $input['csrf']))
			return ["error"=>"The page has expired"];
		if($additional && is_callable($additional))
			return $additional();
	}
}

?>