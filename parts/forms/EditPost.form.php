<?php
require_once("src/classes/form/Form.interface.php");
require_once("src/classes/form/FormBuilder.class.php");

class EditPostForm implements Form{
	private $_decript;

	function __construct($s){
		$this->_decript = $s;
	}

    function getInputs(){
		return [
			new Input(["type"=>"textarea", "maxlenfth"=>500, "value"=>$this->_decript,"name"=>"decription", "_label"=>"Describe your post:"])
        ];
	}
	function getMethod(){
		return "post";
	}
	function getSecret(){
		return "update_post";
	}
	function getSubmitLabel(){
		return "Update";
	}
	function getSubmitClass(){
		return "";
	}
	function getSubmitID(){
		return "";
	}
}

?>